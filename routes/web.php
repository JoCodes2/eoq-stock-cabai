<?php

use App\Http\Controllers\CMS\AuthController;
use App\Http\Controllers\CMS\DashboardController;
use App\Http\Controllers\CMS\EOQController;
use App\Http\Controllers\CMS\MasterController;
use App\Http\Controllers\CMS\PermintaanController;
use App\Http\Controllers\CMS\ProductController;
use App\Http\Controllers\CMS\RequestOilController;
use App\Http\Controllers\CMS\RequestSupplyController;
use App\Http\Controllers\CMS\StokmasukController;
use App\Http\Controllers\CMS\UserController;
use Illuminate\Support\Facades\Route;


// ui web
Route::get('/', function () {
    return view('Pages.home');
});
Route::get('/login', function () {
    return view('Auth.login');
})->middleware('guest');
Route::get('/register', function () {
    return view('Auth.register');
});
Route::post('v1/login', [AuthController::class, 'login'])->name('login');
Route::prefix('v1/user')->controller(UserController::class)->group(function () {
    Route::get('/', 'getAllData');
    Route::post('/create', 'createData');
    Route::get('/get/{id}', 'getDataById');

    Route::delete('/delete/{id}', 'deleteData');
});


Route::middleware(['auth', 'web'])->group(function () {
    // pemebli
    Route::get('/form-request', function () {
        return view('Pages.form-request');
    })->middleware('role:pembeli');
    Route::get('/data-request', function () {
        return view('Pages.data-request');
    })->middleware('role:pembeli');
    // admin
    Route::get('/home', function () {
        return view('Admin.dashboard');
    });
    Route::get('/master-data', function () {
        return view('Admin.master-data');
    });
    Route::get('/user', function () {
        return view('Admin.user');
    });
    Route::get('/stok-masuk', function () {
        return view('Admin.stok-masuk');
    });
    Route::get('/request', function () {
        return view('Admin.Request');
    });
    Route::get('/stok-keluar', function () {
        return view('Admin.stock-out');
    });
    Route::get('/eoq', function () {
        return view('Admin.eoq');
    });
    Route::get('/v1/eoq/config', [EOQController::class, 'index']);

    Route::get('/v1/dashboard/chart-eoq', [EOQController::class, 'getDashboardChart']);
    Route::post('/v1/eoq/config/update/{id}', [EOQController::class, 'updateConfig']);
    Route::get('/v1/eoq/calculate/{master_id}', [EOQController::class, 'calculate']);
    Route::prefix('v1/permintaan')->controller(PermintaanController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/create', 'store');
        Route::get('/show/{id}', 'show');
        Route::get('/nota/{nota}', 'showByNota');
        Route::patch('/update-status/{id}', 'updateStatus');
        Route::delete('/delete/{id}', 'destroy');
        Route::get('/stock-out', 'getAllStockOut');
    });
    Route::prefix('v1')->group(function () {

        // // route  api  //
        Route::prefix('master')->controller(MasterController::class)->group(function () {
            Route::get('/', 'getAllData');
            Route::post('/create', 'createData');
            Route::get('/get/{id}', 'getDataById');
            Route::post('/update/{id}', 'updateDataById');
            Route::delete('/delete/{id}', 'deleteDataById');
        });

        Route::prefix('stokmasuk')->controller(StokmasukController::class)->group(function () {
            Route::get('/', 'getAllData');
            Route::post('/create', 'createData');
            Route::get('/get/{id}', 'getDataById');
            Route::delete('/delete/{id}', 'deleteDataById');
        });
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
