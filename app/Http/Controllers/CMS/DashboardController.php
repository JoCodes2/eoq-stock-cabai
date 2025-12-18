<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        // Hitung jumlah user berdasarkan role
        $adminCount = User::where('role', 'admin')->count();
        $supplierCount = User::where('role', 'supplier')->count();
        $marketCount = User::where('role', 'market')->count();

        // Kirim sebagai response JSON
        return response()->json([
            'success' => true,
            'message' => 'Data dashboard berhasil diambil',
            'data' => [
                'admin' => $adminCount,
                'supplier' => $supplierCount,
                'market' => $marketCount,
            ]
        ]);
    }
}
