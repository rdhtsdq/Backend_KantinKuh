<?php

use App\Http\Controllers\coba;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/product',[ProductController::class,'index']);
Route::post('/product',[ProductController::class,'store']);
Route::get('/product/{kode}',[ProductController::class,'show']);
Route::put('/product/{kode}',[ProductController::class,'update']);
Route::delete('/product/{kode}',[ProductController::class,'destroy']);

Route::get('/keranjang',[KeranjangController::class,'index']);
Route::post('/keranjang',[KeranjangController::class,'store']);
Route::get('/keranjang/{kode_keranjang}',[KeranjangController::class,'show']);
Route::patch('/keranjang/{kode_keranjang}',[KeranjangController::class,'update']);
Route::delete('/keranjang/{kode_keranjang}',[KeranjangController::class,'destroy']);

Route::get('/transaction',[TransactionController::class,'index']);
Route::post('/transaction',[TransactionController::class,'store']);
Route::get('/transaction/{kode_transaksi}',[TransactionController::class,'show']);
Route::put('/transaction/{kode_transaksi}',[TransactionController::class,'update']);
Route::delete('/transaction/{kode_transaksi}',[TransactionController::class,'destroy']);

Route::post('login', [LoginController::class, 'login']);
Route::delete('logout', [LoginController::class, 'logout']);

