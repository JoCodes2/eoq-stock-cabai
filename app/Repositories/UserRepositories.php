<?php

namespace App\Repositories;

use App\Http\Requests\UserRequest;
use App\Interfaces\UserInterfaces;
use App\Models\User;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;




class UserRepositories implements UserInterfaces
{
    use HttpResponseTraits;
    protected $UserModel;
    public function __construct(User $UserModel)
    {
        $this->UserModel = $UserModel;
    }

    public function getAllData()
    {
        $data = $this->UserModel::all();
        if ($data->isEmpty()) {
            return $this->dataNotFound();
        } else {
            return $this->success($data);
        }
    }
    public function createData(UserRequest $request)
    {
        try {
            // Create the user
            $data = new $this->UserModel;
            $data->name = $request->input('name');
            $data->name_market = $request->input('name_market');
            $data->email = $request->input('email');
            $data->role = $request->input('role');
            $data->password = Hash::make($request->input('password'));
            $data->address = $request->input('address');
            $data->phone_number = $request->input('phone_number');

            $data->save();

            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }

    public function getDataById($id)
    {
        $data = $this->UserModel::where('id', $id)->first();
        if ($data) {
            return $this->success($data);
        } else {
            return $this->dataNotFound();
        }
    }

    // public function updateDataById(UserRequest $request, $id)
    // {
    //     try {
    //         // Temukan data pengguna berdasarkan ID
    //         $data = $this->UserModel::findOrFail($id);

    //         // Perbarui data pengguna
    //         $data->name = $request->input('name');
    //         $data->name_market = $request->input('name_market');
    //         $data->email = $request->input('email');
    //         $data->role = $request->input('role');

    //         // Periksa apakah password diisi dan perbarui jika ada
    //         if ($request->filled('password')) {
    //             $data->password = Hash::make($request->input('password'));
    //         }
    //         $data->address = $request->input('address');
    //         $data->phone_number = $request->input('phone_number');


    //         $data->save();

    //         return $this->success($data);
    //     } catch (\Throwable $th) {
    //         return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
    //     }
    // }
    public function updateDataById(UserRequest $request, $id)
    {
        try {
            // Temukan data pengguna berdasarkan ID
            $data = $this->UserModel::findOrFail($id);

            // Cek apakah pengguna yang login adalah admin, supplier, atau market
            $currentUser = Auth::user();
            // Perbaikan di sini

            // Jika pengguna adalah admin, maka admin bisa mengubah semua data, termasuk role
            if ($currentUser->role == 'admin') {
                // Admin bisa mengubah semua data, termasuk role
                $data->name = $request->input('name');
                $data->name_market = $request->input('name_market');
                $data->email = $request->input('email');
                $data->role = $request->input('role'); // Admin dapat mengubah role
            } elseif ($currentUser->role == 'supplier' && $data->role == 'supplier') {
                // Supplier hanya bisa mengubah data dirinya sendiri
                if ($currentUser->id == $data->id) {
                    // Perbarui data supplier
                    $data->name = $request->input('name');
                    $data->name_market = $request->input('name_market');
                    $data->email = $request->input('email');
                } else {
                    return response()->json([
                        'code' => 403,
                        'message' => 'Akses ditolak. Anda hanya bisa mengubah data Anda sendiri.',
                    ], 403);
                }
            } elseif ($currentUser->role == 'market' && $data->role == 'market') {
                // Market hanya bisa mengubah data dirinya sendiri
                if ($currentUser->id == $data->id) {
                    // Perbarui data market
                    $data->name = $request->input('name');
                    $data->name_market = $request->input('name_market');
                    $data->email = $request->input('email');
                } else {
                    return response()->json([
                        'code' => 403,
                        'message' => 'Akses ditolak. Anda hanya bisa mengubah data Anda sendiri.',
                    ], 403);
                }
            } else {
                return response()->json([
                    'code' => 403,
                    'message' => 'Akses ditolak.',
                ], 403);
            }

            if ($request->filled('password')) {
                $data->password = Hash::make($request->input('password'));
            }

            $data->address = $request->input('address');
            $data->phone_number = $request->input('phone_number');

            $data->save();

            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }



    public function deleteData($id)
    {
        try {
            // Temukan data pengguna berdasarkan ID
            $data = $this->UserModel::findOrFail($id);

            // Hapus data pengguna
            $data->delete();

            return $this->success("Data berhasil dihapus.");
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }
}
