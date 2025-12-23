<?php

namespace App\Repositories;

use App\Http\Requests\MasterRequest;
use App\Interfaces\MasterInterfaces;
use App\Models\MasterModel;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Str;

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
        $data = $this->MasterModel::all();
        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }
        return $this->success($data);
    }

    public function createData(MasterRequest $request)
    {
        try {
            $data = new $this->MasterModel;
            $data->kode = 'PRD-' . strtoupper(Str::random(6));
            $data->nama = $request->input('nama');
            $data->satuan = $request->input('satuan');
            $data->jumlah = 0.00;
            $data->stok_minimum = $request->input('stok_minimum', 0);
            $data->harga_beli_terakhir = 0.00;
            $data->harga_jual = $request->input('harga_jual', 0);
            $data->is_aktif = 1;
            $data->save();

            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }

    public function getDataById($id)
    {
        $data = $this->MasterModel::find($id);
        if ($data) {
            return $this->success($data);
        }
        return $this->dataNotFound();
    }

    public function updateDataById(MasterRequest $request, $id)
    {
        try {
            $data = $this->MasterModel::find($id);
            if (!$data) {
                return $this->dataNotFound();
            }

            $data->nama = $request->input('nama');
            $data->satuan = $request->input('satuan');
            $data->stok_minimum = $request->input('stok_minimum');
            if ($request->has('harga_jual')) {
                $data->harga_jual = $request->input('harga_jual');
            }

            $data->save();

            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }

    public function deleteDataById($id)
    {
        try {
            $data = $this->MasterModel::find($id);
            if (!$data) {
                return $this->dataNotFound();
            }

            $data->delete();
            return $this->success("Data berhasil dihapus.");
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }
}
