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
        // 1. Data Penjualan & HPP Aktual (Hanya status 'selesai')
        $salesData = ItemPermintaanModel::join('permintaan', 'item_permintaan.permintaan_id', '=', 'permintaan.id')
            ->join('master_data', 'item_permintaan.master_data_id', '=', 'master_data.id')
            ->where('permintaan.status', 'selesai')
            ->select(
                DB::raw('SUM(item_permintaan.total_harga) as total_omzet'),
                DB::raw('SUM(item_permintaan.jumlah * master_data.harga_beli_terakhir) as total_hpp'),
                DB::raw('SUM(item_permintaan.jumlah) as total_qty_terjual')
            )->first();

        $omzet = (float) ($salesData->total_omzet ?? 0);
        $hpp = (float) ($salesData->total_hpp ?? 0);
        $qtyTerjual = (float) ($salesData->total_qty_terjual ?? 0); // Total Kg yang terjual

        // 2. Hitung Biaya Operasional PROPORSIAL
        // Kita ambil rata-rata biaya per KG dari pengaturan EOQ
        $eoqRates = EOQModel::select(
            DB::raw('AVG(biaya_penyimpanan) as rate_simpan'),
            DB::raw('AVG(biaya_pemesanan / NULLIF(nilai_eoq, 0)) as rate_pesan')
        )->first();

        // Biaya operasional sekarang dikalikan dengan KG yang terjual
        $biayaPenyimpananAktual = $qtyTerjual * (float) ($eoqRates->rate_simpan ?? 0);
        $biayaPemesananAktual = $qtyTerjual * (float) ($eoqRates->rate_pesan ?? 0);

        // 3. Perhitungan Profit yang Akurat
        $keuntunganKotor = $omzet - $hpp;
        $keuntunganBersih = $keuntunganKotor - ($biayaPenyimpananAktual + $biayaPemesananAktual);

        // ... (logic chart_eoq tetap sama)

        return response()->json([
            'summary' => [
                'modal' => (float) StokmasukModel::sum('total_harga'), // Total belanja stok
                'omzet' => $omzet,
                'keuntungan_kotor' => $keuntunganKotor,
                'keuntungan_bersih' => $keuntunganBersih,
                'biaya_penyimpanan' => $biayaPenyimpananAktual,
                'biaya_pemesanan' => $biayaPemesananAktual,
                'perlu_reorder' => $perluReorder
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
