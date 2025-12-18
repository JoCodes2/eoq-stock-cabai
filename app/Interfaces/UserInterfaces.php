<?php

namespace App\Interfaces;

use App\Http\Requests\UserRequest;

interface UserInterfaces
{
    public function getAllData();
    public function createData(UserRequest $request);
    public function getDataById($id);
    public function updateDataById(UserRequest $request, $id);
    public function deleteData($id);
}
