<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Repositories\SupplierRepositories;
use Illuminate\Http\Request;

class RequestSupplyController extends Controller
{
    protected $supplyRepo;
    public function __construct(SupplierRepositories $supplyRepo)
    {
        $this->supplyRepo = $supplyRepo;
    }
    public function getAllData()
    {
        return $this->supplyRepo->getAllData();
    }
    public function createData(Request $request)
    {
        return $this->supplyRepo->createData($request);
    }
    public function getDataById($id)
    {
        return $this->supplyRepo->getDataById($id);
    }
    public function selectOffer($id)
    {
        return $this->supplyRepo->selectOffer($id);
    }
}
