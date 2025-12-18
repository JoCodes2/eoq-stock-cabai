<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Repositories\ProductRepositories;
use Illuminate\Http\Request;

class ProductController
{
    protected $ProductRepo;
    public function __construct(ProductRepositories $ProductRepo)
    {
        $this->ProductRepo = $ProductRepo;
    }
    public function getAllData()
    {
        return $this->ProductRepo->getAllData();
    }
    public function createData(ProductRequest $request)
    {
        return $this->ProductRepo->createData($request);
    }
    public function getDataById($id)
    {
        return $this->ProductRepo->getDataById($id);
    }
    public function updateDataById(ProductRequest $request, $id)
    {
        return $this->ProductRepo->updateDataById($request, $id);
    }
    public function deleteDataById($id)
    {
        return $this->ProductRepo->deleteDataById($id);
    }
}
