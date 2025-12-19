<?php

namespace App\Interfaces;

use App\Http\Requests\StokmasukRequest;
use Illuminate\Http\Request;

interface StokmasukInterfaces
{
    public function getAllData();
    public function createData(StokmasukRequest $request);
    public function getDataById($id);
    public function deleteDataById($id);
}
