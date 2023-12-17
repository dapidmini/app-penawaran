<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PenawaranController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index']);
Route::get('/home/checkSlug', [HomeController::class, 'checkSlug'])->middleware('auth');

Route::get('/login', [LoginController::class, 'index'])->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);

// Route::get('/register', [RegisterController::class, 'index']);
// Route::post('/register', [RegisterController::class, 'store']);

Route::get('/penawaran', [PenawaranController::class, 'index'])->middleware('auth');
Route::get('/penawaran/create', [PenawaranController::class, 'create'])->middleware('auth');

Route::get('/user', [UserController::class, 'index'])->middleware('auth');
Route::get('/user/create', [UserController::class, 'create'])->middleware('auth');
Route::post('/user/create', [UserController::class, 'store'])->middleware('auth');
Route::get('/user/{user:slug}/edit', [UserController::class, 'edit'])->middleware('auth');
Route::put('/user/{user:slug}/edit', [BarangController::class, 'update'])->middleware('auth');
// Route::get('/register', [RegisterController::class, 'index']);
// Route::post('/register', [RegisterController::class, 'store']);

Route::get('/barang', [BarangController::class, 'index']);
Route::get('/barang/create', [BarangController::class, 'create']);
Route::post('/barang/create', [BarangController::class, 'store']);
Route::get('/barang/{barang:slug}/edit', [BarangController::class, 'edit']);
Route::put('/barang/{barang:slug}/edit', [BarangController::class, 'update']);
Route::delete('/barang/{barang:slug}', [BarangController::class, 'destroy']);

// Route::get('/barang/test', [BarangController::class, 'test']);
Route::get('/barang/fetch', [BarangController::class, 'fetchData']);

Route::get('/customer', [CustomerController::class, 'index']);
Route::get('/customer/create', [CustomerController::class, 'create']);
Route::post('/customer/create', [CustomerController::class, 'store']);
Route::get('/customer/{customer:slug}/edit', [CustomerController::class, 'edit']);
Route::put('/customer/{customer:slug}/edit', [CustomerController::class, 'update']);
Route::delete('/customer/{customer:slug}', [CustomerController::class, 'destroy']);