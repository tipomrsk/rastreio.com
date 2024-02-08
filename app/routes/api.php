<?php

use App\Http\Controllers\Companies;
use App\Http\Controllers\Orders;
use App\Http\Controllers\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rota de testes
Route::get('/test', function () {
    return response()->json(['message' => 'Hello World!'], 200);
});


Route::get('/get-data', [User::class, 'getData']);

Route::get('/consult-persist-company', [Companies::class, 'consultPersistCompany']);

Route::get('/consult-persist-orders', [Orders::class, 'consultPersistOrders']);
