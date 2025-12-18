<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestOilRequest;
use App\Repositories\OilRepositories;
use Illuminate\Http\Request;

class RequestOilController extends Controller
{
    protected $oilRepo;

    public function __construct(OilRepositories $oilRepo)
    {
        $this->oilRepo = $oilRepo;
    }
    public function getAllData()
    {
        return $this->oilRepo->getAllData();
    }
    public function createData(RequestOilRequest $request)
    {
        return $this->oilRepo->createData($request);
    }
    public function deleteData($id)
    {
        return $this->oilRepo->deleteData($id);
    }
    public function getUserOilData()
    {
        return $this->oilRepo->getUserOilData();
    }
    public function changeStatus($id)
    {
        return $this->oilRepo->changeStatus($id);
    }
    public function filter(Request $request)
    {
        return $this->oilRepo->filter($request);
    }
}
