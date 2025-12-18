<?php

namespace App\Interfaces;

use App\Http\Requests\RequestOilRequest;
use Illuminate\Http\Request;

interface RequestOilInterfaces
{
    public function getAllData();
    public function createData(RequestOilRequest $request);
    public function deleteData($id);
    public function getUserOilData();
    public function changeStatus($id);
    public function filter(Request $request);
}
