<?php

namespace App\Interfaces;

use App\Http\Requests\MasterRequest;
use Illuminate\Http\Request;

interface  MasterInterfaces
{
    public function getAllData();
    public function createData(MasterRequest $request);
    public function getDataById($id);
    public function updateDataById(MasterRequest $request, $id);
    public function deleteDataById($id);
}
