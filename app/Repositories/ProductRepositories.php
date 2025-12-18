<?php

namespace App\Repositories;

use App\Http\Requests\ProductRequest;
use App\Interfaces\ProductInterfaces;
use App\Models\ProductModel;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Str;



use Illuminate\Support\Facades\Hash;

class ProductRepositories implements ProductInterfaces
{
    use HttpResponseTraits;
    protected $ProductModel;
    public function __construct(ProductModel $ProductModel)
    {
        $this->ProductModel = $ProductModel;
    }

    public function getAllData()
    {
        $data = $this->ProductModel::all();
        if (!$data) {
            return $this->dataNotFound();
        } else {
            return $this->success($data);
        }
    }


    public function createData(ProductRequest $request)
    {
        try {
            // Create the product
            $data = new $this->ProductModel;
            $data->product_name = $request->input('product_name');
            $data->unit = $request->input('unit');

            $data->save();

            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }

    public function getDataById($id)
    {
        $data = $this->ProductModel::where('id', $id)->first();
        if ($data) {
            return $this->success($data);
        } else {
            return $this->dataNotFound();
        }
    }

    public function updateDataById(ProductRequest $request, $id)
    {
        try {
            // Cari data berdasarkan ID
            $data = $this->ProductModel::where('id', $id)->first();
            if (!$data) {
                return $this->dataNotFound();
            }

            // Simpan nama dan harga produk
            $data->product_name = $request->input('product_name');
            $data->unit = $request->input('unit');
            // Simpan perubahan
            $data->update();

            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }


    public function deleteDataById($id)
    {
        try {
            // Temukan data berdasarkan ID
            $data = $this->ProductModel::findOrFail($id);

            $data->delete();

            return $this->success("Data berhasil dihapus.");
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }
}
