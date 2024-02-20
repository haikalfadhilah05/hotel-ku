<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\productsR;
use App\Http\Controllers\transactionsR;
use App\Http\Controllers\dashboardC;
use App\Http\Controllers\loginC;
use App\Http\Controllers\userR;
use App\Http\Controllers\logC;

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

Route::get('/', function () {
    return view('log/login');
});

Route::get('/dashboard', function () {
    $subtittle = "Halaman Dashboard";
    return view('main/dashboard', compact('subtittle'));
})->middleware('auth');

// Pdf
Route::get('user/pdf', [userR::class, 'pdf'])->middleware('userAkses:admin');
Route::get('products/pdf', [productsR::class, 'pdf'])->middleware('userAkses:admin');
Route::get('transactions/pdf', [transactionsR::class, 'pdf'])->middleware('userAkses:owner,kasir');
Route::get('transactions/pdf2/{id}', [transactionsR::class, 'pdf2'])->middleware('userAkses:owner,kasir');

// Products
Route::get('/products/reset/{id}', [productsR::class, 'reset'])->name('products.reset');
Route::resource('products', productsR::class)->middleware('userAkses:admin,owner,kasir');

// transactions
Route::get('update-product-status/{id}', [transactionsR::class, 'updateProductStatus']);
Route::get('pertanggal', [transactionsR::class, 'pertanggal'])->name('transactions.pertanggal')->middleware('userAkses:owner');
Route::get('transactions/tgl', [transactionsR::class, 'tgl'])->name('transactions.tgl')->middleware('userAkses:owner');
Route::resource('transactions', transactionsR::class)->middleware('userAkses:admin,owner,kasir');

// User
Route::resource('user', userR::class);
Route::get('user/changepassword/{id}', [userR::class, 'changepassword'])->name('user.changepassword');
Route::put('user/change/{id}', [userR::class, 'change'])->name('user.change');

// Login
Route::get('login', [loginC::class, 'login'])->name('login')->middleware('guest');
Route::post('login', [loginC::class, 'login_action'])->name('login.action')->middleware('guest');

// Logout
Route::get('logout', [loginC::class, 'logout'])->name('logout')->middleware('auth');

// Log
Route::get('log', [logC::class, 'index'])->name('log.index')->middleware('auth');

// Dashboard
Route::get('dashboard', [dashboardC::class, 'index'])->name('dashboard.index')->middleware('auth');
