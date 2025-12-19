<?php

namespace App\Repositories;


use App\Http\Requests\StokmasukRequest;
use App\Interfaces\StokmasukInterfaces;
use App\Models\MasterModel;
use App\Models\StokmasukModel;
use App\Traits\HttpResponseTraits;

use Illuminate\Support\Facades\DB;


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
        $data = $this->StokmasukModel::with('masterData')
            ->latest()
            ->get();

        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }



    // public function createData(StokmasukRequest $request)
    // {
    //     try {
    //         // Ambil data valid
    //         $validated = $request->validated();

    //         // Hitung total harga otomatis
    //         $validated['total_harga'] =
    //             $validated['jumlah'] * $validated['harga_beli_satuan'];

    //         // Simpan data
    //         $data = $this->StokmasukModel::create([
    //             'kode_stok_masuk'   => $validated['kode_stok_masuk'],
    //             'master_data_id'    => $validated['master_data_id'],
    //             'jumlah'            => $validated['jumlah'],
    //             'harga_beli_satuan' => $validated['harga_beli_satuan'],
    //             'nama_supplier'     => $validated['nama_supplier'],
    //             'total_harga'       => $validated['total_harga'],
    //             'no_invoice'        => $validated['no_invoice'],
    //         ]);

    //         return $this->success($data);
    //     } catch (\Throwable $th) {
    //         return $this->error(
    //             $th->getMessage(),
    //             400,
    //             $th,
    //             class_basename($this),
    //             __FUNCTION__
    //         );
    //     }
    // }

    public function createData(StokmasukRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            // hitung total harga
            $validated['total_harga'] =
                $validated['jumlah'] * $validated['harga_beli_satuan'];

            // simpan stok masuk
            $stokMasuk = $this->StokmasukModel::create([
                'kode_stok_masuk'   => $validated['kode_stok_masuk'],
                'master_data_id'    => $validated['master_data_id'],
                'jumlah'            => $validated['jumlah'],
                'harga_beli_satuan' => $validated['harga_beli_satuan'],
                'nama_supplier'     => $validated['nama_supplier'],
                'total_harga'       => $validated['total_harga'],
                'no_invoice'        => $validated['no_invoice'],
            ]);

            // 🔥 UPDATE STOK MASTER
            $master = MasterModel::findOrFail($validated['master_data_id']);
            $master->jumlah += $validated['jumlah'];
            $master->save();

            DB::commit();
            return $this->success($stokMasuk);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error(
                $th->getMessage(),
                400,
                $th,
                class_basename($this),
                __FUNCTION__
            );
        }
    }


    public function getDataById($id)
    {
        $data = $this->StokmasukModel::where('id', $id)->first();
        if ($data) {
            return $this->success($data);
        } else {
            return $this->dataNotFound();
        }
    }

    // public function deleteDataById($id)
    // {
    //     try {
    //         // Temukan data berdasarkan ID
    //         $data = $this->StokmasukModel::findOrFail($id);

    //         $data->delete();

    //         return $this->success("Data berhasil dihapus.");
    //     } catch (\Throwable $th) {
    //         return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
    //     }
    // }

    public function deleteDataById($id)
    {
        DB::beginTransaction();

        try {
            $stokMasuk = $this->StokmasukModel::findOrFail($id);

            // ambil master
            $master = MasterModel::findOrFail($stokMasuk->master_data_id);

            // kurangi stok
            $master->jumlah -= $stokMasuk->jumlah;

            // cegah stok minus
            if ($master->jumlah < 0) {
                $master->jumlah = 0;
            }

            $master->save();

            // hapus stok masuk
            $stokMasuk->delete();

            DB::commit();
            return $this->success("Stok masuk berhasil dihapus");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error(
                $th->getMessage(),
                400,
                $th,
                class_basename($this),
                __FUNCTION__
            );
        }
    }
}
