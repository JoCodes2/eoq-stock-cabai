<?php

namespace App\Repositories;

use App\Http\Requests\RequestOilRequest;
use App\Interfaces\RequestOilInterfaces;
use App\Models\RequestOilModel;
use App\Traits\HttpResponseTraits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OilRepositories implements RequestOilInterfaces
{
    use HttpResponseTraits;
    protected $oilModel;
    public function __construct(RequestOilModel $oilModel)
    {
        $this->oilModel = $oilModel;
    }
    public function getAllData()
    {
        $data = $this->oilModel::with(['product', 'market', 'supplier', 'supllyOffers'])->get();
        if (!$data) {
            return $this->dataNotFound();
        }
        return $this->success($data);
    }
    public function getUserOilData()
    {
        $user = Auth::user();

        $data = $this->oilModel::with(['product', 'market', 'supplier'])
            ->where('market_id', $user->id)
            ->get();

        if (!$data) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }
    public function createData(RequestOilRequest $request)
    {
        try {
            $user = Auth::user();
            $data = new $this->oilModel;
            $data->market_id = $user->id;
            $data->product_id = $request->input('product_id');
            $data->request_date = Carbon::now('Asia/Makassar');
            $data->end_time = $request->input('end_time');
            $data->quantity = $request->input('quantity');
            $data->description = $request->input('description');
            $data->status_request = 'open';
            $data->save();
            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }
    public function deleteData($id)
    {
        $data = $this->oilModel::find($id);
        if (!$data) {
            return $this->dataNotFound();
        }
        $data->delete();
        return $this->delete();
    }
    public function changeStatus($id)
    {
        $data = $this->oilModel::find($id);
        if (!$data) {
            return $this->dataNotFound();
        }
        $data->status_request = 'close';
        $data->save();
        return $this->success($data);
    }
    public function filter(Request $request)
    {
        $status = $request->input('status_request');

        $query = $this->oilModel::with(['product', 'market', 'supllyOffers']);

        if ($status) {
            $query->where('status_request', $status);
        }

        $requests = $query->get();
        return $this->success($requests);
    }
}
