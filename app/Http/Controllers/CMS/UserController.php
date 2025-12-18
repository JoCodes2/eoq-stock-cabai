<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Repositories\UserRepositories;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $UserRepo;
    public function __construct(UserRepositories $UserRepo)
    {
        $this->UserRepo = $UserRepo;
    }
    public function getAllData()
    {
        return $this->UserRepo->getAllData();
    }
    public function createData(UserRequest $request)
    {
        return $this->UserRepo->createData($request);
    }
    public function getDataById($id)
    {
        return $this->UserRepo->getDataById($id);
    }
    public function updateDataById(UserRequest $request, $id)
    {
        return $this->UserRepo->updateDataById($request, $id);
    }
    public function deleteData($id)
    {
        return $this->UserRepo->deleteData($id);
    }
}
