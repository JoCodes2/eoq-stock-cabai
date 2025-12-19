<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\StokmasukRequest;
use App\Repositories\StokmasukRepositories;
use Illuminate\Http\Request;

class StokmasukController extends Controller
{
     protected $StokmasukRepo;
    public function __construct(StokmasukRepositories $StokmasukRepo)
    {
        $this->StokmasukRepo = $StokmasukRepo;
    }
    public function getAllData()
    {
        return $this->StokmasukRepo->getAllData();
    }
    public function createData(StokmasukRequest $request)
    {
        return $this->StokmasukRepo->createData($request);
    }
    public function getDataById($id)
    {
        return $this->StokmasukRepo->getDataById($id);
    }
    public function deleteDataById($id)
    {
        return $this->StokmasukRepo->deleteDataById($id);
    }
}
