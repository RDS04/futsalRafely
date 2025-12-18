<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BokingController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InputLapanganController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('costumers.dashboard-padang');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'ShowLogin')->name('login');
    Route::get('/admin/login', 'ShowLoginAdmin')->name('loginAdmin');
    Route::post('/login', 'login')->name('login.post');
    Route::post('/admin/login', 'adminLogin')->name('admin.login.post');
    Route::get('/register', 'Register')->name('register');
    Route::post('/register', 'store')->name('register.store');
    Route::post('/logout', 'logout')->name('logout');

    Route::get('/admin/register', 'adminRegister')->name('admin.register.show');
    Route::post('/admin/register', 'storeAdmin')->name('admin.register.store');
});
Route::controller(DashboardController::class)->middleware('admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', 'homeAdmin')->name('admin.dashboard');
    Route::get('/dashboard/padang', 'adminPadang')->name('adminPadang');
    Route::get('/dashboard/sijunjung', 'adminSijunjung')->name('adminSijunjung');
    Route::get('/dashboard/bukittinggi', 'adminBukittinggi')->name('adminBukittinggi');
});

Route::controller(CostumerController::class)->middleware('auth')->group(function () {
    Route::get('/dashboard/padang', 'padang')->name('costumers.dashboard.padang');
    Route::get('/dashboard/sijunjung', 'sijunjung')->name('costumers.dashboard.sijunjung');
    Route::get('/dashboard/bukittinggi', 'bukittinggi')->name('costumers.dashboard.bukittinggi');
});

Route::controller(BokingController::class)->prefix('boking')->group(function () {
    Route::get('/', 'bookingForm')->name('boking.form');
    Route::get('/bokingForm', 'bookingForm')->name('boking.form');
    Route::post('/bokingForm', 'store')->name('boking.store');

    Route::get('/payment', 'payment')->name('show.payment');
});

Route::controller(InputLapanganController::class)->middleware('admin')->prefix('input-lapangan')->group(function () {
    Route::get('/inputLapangan', 'inputLapangan')->name('inputLapangan.padang');
    Route::post('/inputLapangan', 'store')->name('lapangan.store');
});