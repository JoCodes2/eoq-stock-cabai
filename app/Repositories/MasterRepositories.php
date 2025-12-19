<?php

namespace App\Repositories;

use App\Http\Requests\MasterRequest;
use App\Interfaces\MasterInterfaces;
use App\Models\MasterModel;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Hash;

class MasterRepositories implements MasterInterfaces
{
    use HttpResponseTraits;
    protected $MasterModel;
    public function __construct(MasterModel $MasterModel)
    {
        $this->MasterModel = $MasterModel;
    }

    public function getAllData()
    {
        // $data = $this->MasterModel::all();
        // if (!$data) {
        //     return $this->dataNotFound();
        // } else {
        //     return $this->success($data);
        // }

        $data = $this->MasterModel::all();

        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }


    public function createData(MasterRequest $request)
    {
        try {
            // Create the product
            $data = new $this->MasterModel;
            $data->kode = 'PRD-' . strtoupper(Str::random(6)); // ✅ FIX
            $data->nama = $request->input('nama');
            $data->satuan = $request->input('satuan');
            $data->jumlah = 0.00;
            $data->stok_minimum = 0.00;
            $data->is_aktif = 1;


            $data->save();

            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }

    public function getDataById($id)
    {
        $data = $this->MasterModel::where('id', $id)->first();
        if ($data) {
            return $this->success($data);
        } else {
            return $this->dataNotFound();
        }
    }

    public function updateDataById(MasterRequest $request, $id)
    {
        try {
            // Cari data berdasarkan ID
            $data = $this->MasterModel::where('id', $id)->first();
            if (!$data) {
                return $this->dataNotFound();
            }

            // Simpan nama dan harga produk
            // $data->kode = $request->input('kode');
            $data->nama = $request->input('nama');
            $data->satuan = $request->input('satuan');
            // $data->jumlah = $request->input('jumlah');
            // $data->stok_minimum = $request->input('stok_minimum');
            // $data->is_aktif = 1;
            // Simpan perubahan
            // $data->update();
            $data->save();

            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }


    public function deleteDataById($id)
    {
        try {
            // Temukan data berdasarkan ID
            $data = $this->MasterModel::findOrFail($id);

            $data->delete();

            return $this->success("Data berhasil dihapus.");
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }
}
