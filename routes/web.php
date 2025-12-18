<?php

use App\Http\Controllers\CMS\AuthController;
use App\Http\Controllers\CMS\DashboardController;
use App\Http\Controllers\CMS\ProductController;
use App\Http\Controllers\CMS\RequestOilController;
use App\Http\Controllers\CMS\RequestSupplyController;
use App\Http\Controllers\CMS\UserController;
use Illuminate\Support\Facades\Route;



Route::get('/home', function () {
    return view('Admin.dashboard');
});
Route::get('/master-data', function () {
    return view('Admin.master-data');
});

Route::get('/user', function () {
    return view('Admin.user');
});
// Route::prefix('v1')->group(function () {

//     // route  api  //
//     Route::prefix('product')->controller(ProductController::class)->group(function () {
//         Route::get('/', 'getAllData');
//         Route::post('/create', 'createData');
//         Route::get('/get/{id}', 'getDataById');
//         Route::post('/update/{id}', 'updateDataById');
//         Route::delete('/delete/{id}', 'deleteDataById');
//     });

//     Route::prefix('oil')->controller(RequestOilController::class)->group(function () {
//         Route::get('/', 'getAllData');
//         Route::delete('/delete/{id}', 'deleteData');
//         Route::post('/change/{id}', 'changeStatus');
//         Route::get('/filter', 'filter');
//     });
//     Route::prefix('user')->controller(UserController::class)->group(function () {
//         Route::get('/', 'getAllData');
//         Route::post('/create', 'createData');
//         Route::delete('/delete/{id}', 'deleteData');
//     });
// });


// // ui web
// Route::get('/', function () {
//     return view('Pages.home');
// });
// Route::get('/request-oil', function () {
//     return view('Pages.request');
// });





// // ui web
// Route::get('/profile', function () {
//     return view('Pages.profile');
// });
// Route::get('/data-request-market', function () {
//     return view('Pages.dataMarket');
// });
// Route::get('/data-request-suplier', function () {
//     return view('Pages.suplierRequest');
// });
// Route::get('/form-request', function () {
//     return view('Pages.form-request');
// })->middleware('role:supplier,market');
// Route::get('/offers', function () {
//     return view('Pages.offers');
// });
// Route::prefix('v1/supply')->controller(RequestSupplyController::class)->group(function () {
//     Route::get('/', 'getAllData');
//     Route::post('/create', 'createData');
//     Route::get('/get/{id}', 'getDataById');
//     Route::post('/change/{id}', 'selectOffer');
// });

// // route  api  //
// Route::post('v1/user/update/{id}', [UserController::class, 'updateDataById']);

// Route::post('v1/oil/create', [RequestOilController::class, 'createData']);

// Route::get('v1/user/get/{id}', [UserController::class, 'getDataById']);

// Route::get('v1/get-oil-user', [RequestOilController::class, 'getUserOilData']);

// Route::post('v1/logout', [AuthController::class, 'logout']);
// Route::get('v1/dashboard', [DashboardController::class, 'index']);
