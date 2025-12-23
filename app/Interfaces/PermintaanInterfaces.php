<?php

namespace App\Interfaces;

use App\Http\Requests\PermintaanRequest;

interface PermintaanInterfaces
{
    public function getAllData();
    public function createData(PermintaanRequest $request);
    public function getDataById($id);
    public function getByNota($nomor_permintaan);
    public function updateStatus($id, $status);
    public function deleteData($id);
}
