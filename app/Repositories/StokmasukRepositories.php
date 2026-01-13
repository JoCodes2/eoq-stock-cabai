<?php

namespace App\Repositories;

use App\Http\Requests\StokmasukRequest;
use App\Interfaces\StokmasukInterfaces;
use App\Models\MasterModel;
use App\Models\StokmasukModel;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StokmasukRepositories implements StokmasukInterfaces
{
    use HttpResponseTraits;
    protected $StokmasukModel;

    public function __construct(StokmasukModel $StokmasukModel)
    {
        $this->StokmasukModel = $StokmasukModel;
    }

    public function getAllData()
    {
        // Menggunakan eager loading 'masterData' agar nama cabai muncul
        $data = $this->StokmasukModel::with('masterData')->latest()->get();

        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }

    public function createData(StokmasukRequest $request)
    {
        DB::beginTransaction();

        try {
            // Hitung total harga otomatis
            $total_harga = bcmul($request->jumlah, $request->harga_beli_satuan, 2);

            $kodeOtomatis = 'SM-' . date('Ymd') . '-' . Str::random(4);

            // 1. Simpan data stok masuk
            $stokMasuk = $this->StokmasukModel::create([
                'kode_stok_masuk'   => $kodeOtomatis,
                'master_data_id'    => $request->master_data_id,
                'jumlah'            => $request->jumlah,
                'harga_beli_satuan' => $request->harga_beli_satuan,
                'nama_supplier'     => $request->nama_supplier,
                'total_harga'       => $total_harga,
                'no_invoice'        => $request->no_invoice,
            ]);

            $master = MasterModel::findOrFail($request->master_data_id);

            // Tambah stok fisik
            $master->jumlah += $request->jumlah;

            // Update harga beli terakhir (Penting untuk perhitungan modal EOQ)
            $master->harga_beli_terakhir = $request->harga_beli_satuan;

            $master->save();

            DB::commit();
            return $this->success($stokMasuk);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }

    public function getDataById($id)
    {
        $data = $this->StokmasukModel::with('masterData')->find($id);
        if ($data) {
            return $this->success($data);
        }
        return $this->dataNotFound();
    }

    public function deleteDataById($id)
    {
        DB::beginTransaction();

        try {
            $stokMasuk = $this->StokmasukModel::findOrFail($id);
            $master = MasterModel::findOrFail($stokMasuk->master_data_id);

            // 1. Kurangi stok di master (Reversal)
            $master->jumlah -= $stokMasuk->jumlah;

            // Cegah stok menjadi minus jika ternyata barang sudah terpakai
            if ($master->jumlah < 0) {
                throw new \Exception("Gagal menghapus! Stok sudah digunakan untuk transaksi lain.");
            }

            $master->save();

            // 2. Hapus transaksi stok masuk
            $stokMasuk->delete();

            DB::commit();
            return $this->success("Transaksi stok masuk berhasil dibatalkan (dihapus).");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }
}
