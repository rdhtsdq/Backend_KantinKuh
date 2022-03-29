<?php

use App\Http\Controllers\coba;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\log_and_trigger;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
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


Route::get('/product',[ProductController::class,'index']);
Route::get('/product/{kode}',[ProductController::class,'show']);

Route::get('/keranjang',[KeranjangController::class,'index']);
Route::get('/keranjang/{kode_keranjang}',[KeranjangController::class,'show']);
Route::post('/keranjang',[KeranjangController::class,'store']);
Route::patch('/keranjang/{kode_keranjang}',[KeranjangController::class,'update']);

Route::get('/transaction',[TransactionController::class,'index']);
Route::post('/transaction',[TransactionController::class,'store']);
Route::get('/transaction/{kode_transaksi}',[TransactionController::class,'show']);

//Route::post('login', [LoginController::class, 'login']);
//Route::delete('logout', [LoginController::class, 'logout']);

Route::post("/g",[KeranjangController::class,'storegambar']);

Route::get("pass", function() { return bcrypt('rakunTua'); });

Route::group(['middleware' => 'web'], function() {
  Route::get('/csrf', function(Request $request) {
    return response()->json(['token' => csrf_token()]); 
  });
});


Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
  // route Auth
  Route::post('login', [AuthController::class, 'login']);
  Route::post('logout', [AuthController::class, 'logout']);
  Route::post('refresh', [AuthController::class, 'refresh']);
  Route::post('me', [AuthController::class, 'me']);

  // route produk
  Route::patch('/product/{kode}',[ProductController::class,'update']);
  Route::delete('/product/{kode}',[ProductController::class,'destroy']);
  Route::post('/product',[ProductController::class,'store']);

  // route keranjang
  Route::delete('/keranjang/{kode_keranjang}',[KeranjangController::class,'destroy']);

  // route transaksi
  Route::patch('/transaction/{kode_transaksi}',[TransactionController::class,'update']);
  Route::delete('/transaction/{kode_transaksi}',[TransactionController::class,'destroy']);

  // route log
  Route::get('/log/user',[log_and_trigger::class,'log_user']);
  Route::get('/log/product',[log_and_trigger::class,'log_product']);
  Route::get('/log/view',[log_and_trigger::class,'SelectView']);
  Route::get('/log/view/laporan',[log_and_trigger::class,'ShowView']);
});
