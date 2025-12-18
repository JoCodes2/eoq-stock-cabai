<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface RequestSupplyInterfaces
{
    public function getAllData();
    public function  createData(Request $request);
    public function getDataById($id);
    public function selectOffer($id);
}
