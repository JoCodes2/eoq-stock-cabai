<?php

namespace App\Interfaces;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;

interface  ProductInterfaces
{
    public function getAllData();
    public function createData(ProductRequest $request);
    public function getDataById($id);
    public function updateDataById(ProductRequest $request, $id);
    public function deleteDataById($id);
}
