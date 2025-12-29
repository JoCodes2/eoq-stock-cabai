<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermintaanRequest;
use App\Interfaces\PermintaanInterfaces;
use App\Repositories\PermintaanRepositories;
use Illuminate\Http\Request;

class PermintaanController extends Controller
{
    protected $permintaanRepository;

    public function __construct(PermintaanRepositories $permintaanRepository)
    {
        $this->permintaanRepository = $permintaanRepository;
    }

    public function index()
    {
        return $this->permintaanRepository->getAllData();
    }

    public function store(PermintaanRequest $request)
    {
        return $this->permintaanRepository->createData($request);
    }

    public function show($id)
    {
        return $this->permintaanRepository->getDataById($id);
    }

    public function showByNota($nota)
    {
        return $this->permintaanRepository->getByNota($nota);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,dikirim,selesai,ditolak'
        ]);

        return $this->permintaanRepository->updateStatus($id, $request->status);
    }

    public function destroy($id)
    {
        return $this->permintaanRepository->deleteData($id);
    }
    public function getAllStockOut()
    {
        return $this->permintaanRepository->getAllStokKeluar();
    }
}
