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
        // 1. Data Penjualan & HPP Aktual
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
        $qtyTerjual = (float) ($salesData->total_qty_terjual ?? 0);

        // 2. Nilai Stok Saat Ini
        $stokData = DB::table('master_data')
            ->select(
                DB::raw('SUM(jumlah * harga_beli_terakhir) as nilai_stok_total')
            )->first();

        $nilaiStokTotal = (float) ($stokData->nilai_stok_total ?? 0);

        // 3. PERHITUNGAN BIAYA YANG BENAR:
        // Ambil rata-rata persentase biaya penyimpanan (dalam %)
        $avgEOQ = EOQModel::select(
            DB::raw('AVG(biaya_penyimpanan) as avg_biaya_simpan_percent')
        )->first();

        // Asumsi: biaya penyimpanan dalam EOQ adalah % per TAHUN
        // Konversi ke biaya bulanan (lebih realistis untuk dashboard)
        $persentasePenyimpananPerTahun = (float) ($avgEOQ->avg_biaya_simpan_percent ?? 0);

        // JIKA biaya_penyimpanan dalam bentuk nominal (bukan persentase)
        // Cek nilai tipikal: jika > 100, kemungkinan nominal, jika <= 100 kemungkinan persentase
        if ($persentasePenyimpananPerTahun > 100) {
            // Jika nominal, konversi ke persentase dari nilai stok
            $biayaPenyimpananAktual = $persentasePenyimpananPerTahun / 12; // Jadi bulanan
        } else {
            // Jika persentase, hitung dari nilai stok
            $biayaPenyimpananAktual = ($nilaiStokTotal * $persentasePenyimpananPerTahun / 100) / 12;
        }

        // Batasi biaya penyimpanan maksimal 10% dari omzet (logika bisnis)
        $maxPenyimpanan = $omzet * 0.10;
        $biayaPenyimpananAktual = min($biayaPenyimpananAktual, $maxPenyimpanan);

        // 4. Biaya Pemesanan (lebih realistis)
        // Hitung berdasarkan jumlah transaksi pemesanan stok
        $jumlahPemesanan = StokmasukModel::count();
        $avgBiayaPesan = EOQModel::avg('biaya_pemesanan') ?? 0;

        // Biaya pemesanan per transaksi
        $biayaPemesananAktual = $avgBiayaPesan * $jumlahPemesanan;

        // 5. Perhitungan Profit
        $keuntunganKotor = $omzet - $hpp;
        $keuntunganBersih = $keuntunganKotor - ($biayaPenyimpananAktual + $biayaPemesananAktual);

        // 6. Modal (investasi stok)
        $modal = (float) StokmasukModel::sum('total_harga');

        // 7. Data Chart EOQ
        $perluReorder = 0;
        $eoqAnalytics = EOQModel::with('masterData')->get()->map(function ($item) use (&$perluReorder) {
            $stokRiil = (float) ($item->masterData->jumlah ?? 0);
            $rop = (float) ($item->titik_pemesanan_ulang ?? 0);
            if ($stokRiil <= $rop && $item->terakhir_dihitung != null) $perluReorder++;

            return [
                'nama' => $item->masterData->nama,
                'stok_sekarang' => $stokRiil,
                'rop' => $rop,
                'eoq' => (float) $item->nilai_eoq,
                'status' => ($stokRiil <= $rop) ? 'Reorder' : 'Aman'
            ];
        });

        return response()->json([
            'summary' => [
                'modal_investasi' => $modal,
                'omzet' => $omzet,
                'hpp' => $hpp,
                'keuntungan_kotor' => $keuntunganKotor,
                'keuntungan_bersih' => max($keuntunganBersih, 0), // Tidak boleh negatif
                'biaya_penyimpanan' => $biayaPenyimpananAktual,
                'biaya_pemesanan' => $biayaPemesananAktual,
                'perlu_reorder' => $perluReorder,
                'margin_kotor' => ($omzet > 0) ? round(($keuntunganKotor / $omzet) * 100, 2) : 0,
                'margin_bersih' => ($omzet > 0) ? round(($keuntunganBersih / $omzet) * 100, 2) : 0,
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
