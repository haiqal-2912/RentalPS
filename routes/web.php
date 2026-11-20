<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
    //return view('welcome');});
Route::get('/', [BerandaController::class, 'index'])->name('beranda')->middleware('auth');
Route::get('register', [RegisterController::class, 'index'])->name('register');
Route::post('register', [RegisterController::class, 'store'])->name('register.store');
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'proses'])->name('login.proses');
Route::get('login/keluar', [LoginController::class, 'keluar'])->name('login.keluar');

Route::get('users', function(){
    return view('users.index');
})->name('users')->middleware(['auth', 'role:kasir']);
Route::get('playstation', function(){
    return view('playstation.index');
})->name('playstation')->middleware('auth');
Route::get('transaksi', function(){
    return view('transaksi.index');
})->name('transaksi')->middleware('auth');
Route::get('laporan', function(){
    return view('laporan.index');
})->name('laporan')->middleware(['auth', 'role:kasir']);

// Menu makanan & minuman: kasir mengelola (CRUD), pelanggan melihat menu tersedia lalu memesan
Route::get('menu', function(){
    return view('menu.index');
})->name('menu')->middleware(['auth', 'role:kasir,pelanggan']);

// Pesanan menu: kasir melihat semua & mengubah status, pelanggan hanya melihat pesanannya sendiri
Route::get('pesanan', function(){
    return view('pesanan.index');
})->name('pesanan')->middleware(['auth', 'role:kasir,pelanggan']);

// Live Slot Board & Katalog: bisa dibuka tanpa login (tidak menampilkan data pribadi)
Route::get('jadwal', function(){
    return view('jadwal.index');
})->name('jadwal');
Route::get('katalog', [\App\Http\Controllers\KatalogController::class, 'index'])->name('katalog');

// Nota digital: kasir semua transaksi, pelanggan hanya miliknya
Route::get('transaksi/{transaksi}/nota', [\App\Http\Controllers\NotaController::class, 'show'])
    ->name('transaksi.nota')->middleware('auth');
