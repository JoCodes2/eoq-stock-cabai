<?php

namespace App\Repositories;

use App\Interfaces\RequestSupplyInterfaces;
use App\Models\RequestSupplyModel;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierRepositories implements RequestSupplyInterfaces
{
    use HttpResponseTraits;
    protected $supllyModel;
    public function __construct(RequestSupplyModel $supllyModel)
    {
        $this->supllyModel = $supllyModel;
    }
    public function getAllData()
    {
        $user = Auth::user();

        $query = $this->supllyModel->with(['request.product', 'request.market', 'supplier']);

        if ($user->role === 'supplier') {
            $query->where('supplier_id', $user->id);
        } elseif ($user->role === 'market') {
            $query->whereHas('request', function ($q) use ($user) {
                $q->where('market_id', $user->id);
            });
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        return $this->success($data);
    }

    public function createData(Request $request)
    {
        try {
            $user = Auth::user();
            $data = new $this->supllyModel;
            $data->supplier_id = $user->id;
            $data->request_id = $request->input('request_id');
            $data->status = 'pending';

            $data->save();
            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }
    public function getDataById($id)
    {
        $data = $this->supllyModel
            ->with(['request.product', 'supplier'])
            ->where('id', $id)
            ->first();

        if ($data) {
            return $this->success($data);
        } else {
            return $this->dataNotFound();
        }
    }
    public function selectOffer($id)
    {
        try {
            // Ambil penawaran yang dipilih
            $selectedOffer = $this->supllyModel->with('request')->find($id);

            if (!$selectedOffer) {
                return $this->dataNotFound();
            }

            // Update status offer jadi 'selected'
            $selectedOffer->status = 'selected';
            $selectedOffer->save();

            // Hapus semua offer lain yang bukan yang dipilih, tapi request_id sama
            $this->supllyModel
                ->where('request_id', $selectedOffer->request_id)
                ->where('id', '!=', $id)
                ->delete();

            // Update data di tabel requests
            $request = $selectedOffer->request;
            $request->selected_supplier_id = $selectedOffer->supplier_id;
            $request->status_request = 'fulfilled'; // atau 'close' kalau kamu mau
            $request->save();

            return $this->success($selectedOffer, 'Penawaran berhasil dipilih, data lain dihapus, dan request diperbarui.');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500, $th, class_basename($this), __FUNCTION__);
        }
    }
}
