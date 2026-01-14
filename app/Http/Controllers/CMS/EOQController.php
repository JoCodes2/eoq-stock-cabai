<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\EOQModel;
use App\Models\ItemPermintaanModel;
use App\Models\MasterModel;
use App\Models\StokKeluarModel;
use App\Models\StokmasukModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EOQController extends Controller
{
    public function getDashboardChart()
    {
        // 1. Total Modal Keseluruhan (Uang yang telah dibelanjakan untuk stok)
        $totalModal = StokmasukModel::sum('total_harga') ?? 0;

        // 2. Data Penjualan & HPP Aktual (Hanya dari transaksi yang statusnya 'selesai')
        $salesData = ItemPermintaanModel::join('permintaan', 'item_permintaan.permintaan_id', '=', 'permintaan.id')
            ->join('master_data', 'item_permintaan.master_data_id', '=', 'master_data.id')
            ->where('permintaan.status', 'selesai')
            ->select(
                DB::raw('SUM(item_permintaan.total_harga) as total_omzet'),
                // HPP dihitung dari (Jumlah terjual * Harga beli terakhir)
                DB::raw('SUM(item_permintaan.jumlah * master_data.harga_beli_terakhir) as total_hpp'),
                DB::raw('SUM(item_permintaan.jumlah) as total_qty_terjual')
            )->first();

        $omzet = (float) ($salesData->total_omzet ?? 0);
        $hpp = (float) ($salesData->total_hpp ?? 0);
        $qtyTerjual = (float) ($salesData->total_qty_terjual ?? 0);

        // 3. Keuntungan Kotor (Margin murni penjualan)
        $grossProfit = $omzet - $hpp;

        /** * CATATAN: Biaya EOQ adalah biaya PROYEKSI/ESTIMASI tahunan.
         * Jika dimasukkan langsung ke Profit/Loss transaksi harian, angka akan berantakan (sering minus).
         * Di sini kita hitung hanya sebagai informasi operasional, bukan pengurang profit utama.
         */
        $eoqRates = EOQModel::select(
            DB::raw('AVG(biaya_penyimpanan) as avg_holding_rate'),
            DB::raw('AVG(biaya_pemesanan / NULLIF(nilai_eoq, 0)) as avg_ordering_rate')
        )->first();

        // Estimasi biaya operasional berdasarkan qty yang benar-benar terjual
        $estBiayaSimpan = $qtyTerjual * (float) ($eoqRates->avg_holding_rate ?? 0);
        $estBiayaPesan  = $qtyTerjual * (float) ($eoqRates->avg_ordering_rate ?? 0);

        // Keuntungan bersih (Setelah dikurangi estimasi biaya operasional per unit terjual)
        $netProfit = $grossProfit - ($estBiayaSimpan + $estBiayaPesan);

        // 4. Analisis Stok & Chart EOQ
        $perluReorder = 0;
        $eoqAnalytics = EOQModel::with('masterData')
            ->get()
            ->map(function ($item) use (&$perluReorder) {
                $stokRiil = (float) ($item->masterData->jumlah ?? 0);
                $rop = (float) ($item->titik_pemesanan_ulang ?? 0);
                $eoq = (float) ($item->nilai_eoq ?? 0);

                // Cek kondisi reorder
                if ($stokRiil <= $rop && $item->terakhir_dihitung != null) {
                    $perluReorder++;
                }

                return [
                    'nama' => $item->masterData->nama ?? 'Produk',
                    'stok_sekarang' => $stokRiil,
                    'rop' => $rop,
                    'eoq' => $eoq,
                    'status' => ($stokRiil <= $rop) ? 'Reorder' : 'Aman'
                ];
            });

        return response()->json([
            'summary' => [
                'total_modal_stok' => (float) $totalModal, // Pengeluaran beli stok
                'omzet'            => $omzet,             // Total penjualan (Selesai)
                'total_hpp'        => $hpp,               // Modal dari barang yang laku
                'keuntungan_kotor' => $grossProfit,       // Omzet - HPP
                'keuntungan_bersih' => $netProfit,        // Keuntungan setelah biaya unit
                'biaya_operasional' => [
                    'penyimpanan' => $estBiayaSimpan,
                    'pemesanan'   => $estBiayaPesan
                ],
                'perlu_reorder'    => $perluReorder
            ],
            'chart_eoq' => $eoqAnalytics
        ]);
    }
    public function index()
    {
        $allMasterId = MasterModel::pluck('id');
        foreach ($allMasterId as $id) {
            EOQModel::firstOrCreate(
                ['master_data_id' => $id],
                [
                    'permintaan_tahunan' => 0,
                    'biaya_pemesanan' => 0,
                    'biaya_penyimpanan' => 0,
                    'waktu_tunggu_hari' => 1,
                    'stok_aman' => 0
                ]
            );
        }

        $data = EOQModel::with('masterData')->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function updateConfig(Request $request, $id)
    {
        try {
            $config = EOQModel::findOrFail($id);
            $config->update([
                'biaya_pemesanan'   => $request->biaya_pemesanan,
                'biaya_penyimpanan' => $request->biaya_penyimpanan,
                'waktu_tunggu_hari' => $request->waktu_tunggu_hari,
                'stok_aman'         => $request->stok_aman,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Parameter berhasil disimpan'
            ]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 400);
        }
    }
    public function calculate($id)
    {
        try {
            $config = EOQModel::with('masterData')->findOrFail($id);

            $totalKeluar = StokKeluarModel::where('master_data_id', $config->master_data_id)
                ->where('dikeluarkan_pada', '>=', now()->subYear())
                ->sum('jumlah');

            $R = $totalKeluar > 0 ? $totalKeluar : $config->permintaan_tahunan;

            $S = $config->biaya_pemesanan;
            $H = $config->biaya_penyimpanan;
            $LT = $config->waktu_tunggu_hari;
            $SS = $config->stok_aman;

            if ($H <= 0) throw new \Exception("Biaya penyimpanan (H) tidak boleh nol.");

            $eoq = sqrt((2 * $R * $S) / $H);

            $permintaanPerHari = $R / 365;
            $rop = ($permintaanPerHari * $LT) + $SS;

            $config->update([
                'permintaan_tahunan' => $R,
                'nilai_eoq' => $eoq,
                'titik_pemesanan_ulang' => $rop,
                'terakhir_dihitung' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Perhitungan selesai',
                'results' => [
                    'eoq' => round($eoq, 2),
                    'rop' => round($rop, 2),
                    'permintaan_tahunan' => $R
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 400);
        }
    }
}
