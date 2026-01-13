<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BokingController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InputLapanganController;
use Illuminate\Support\Facades\Route;

/**
 * ROUTE WEBSITE PUBLIK (CUSTOMER)
 * Route untuk menampilkan website sesuai region
 * Menggunakan CostumerController untuk mengirim data lapangan, event, slider
 */
Route::controller(CostumerController::class)->group(function () {
    // Redirect root ke padang dashboard
    Route::get('/', 'padang')->name('home');
    
    // Public routes untuk melihat lapangan per region (tanpa auth)
    Route::get('/region/padang', 'padang')->name('web.region.padang');
    Route::get('/region/sijunjung', 'sijunjung')->name('web.region.sijunjung');
    Route::get('/region/bukittinggi', 'bukittinggi')->name('web.region.bukittinggi');
    
    // Redirect /region/{region} ke controller
    Route::get('/region/{region}', function ($region) {
        $region = strtolower($region);
        $validRegions = ['padang', 'sijunjung', 'bukittinggi'];
        
        if (!in_array($region, $validRegions)) {
            abort(404, 'Region tidak ditemukan');
        }
        
        return redirect("region/{$region}");
    })->name('web.region');
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

/**
 * ROUTE ADMIN
 * Middleware: auth.admin -> check admin login
 * 
 * Untuk Master Admin only:
 * - /admin/dashboard (master dashboard)
 * - /admin/manajemen/* (manage regions, admins, etc)
 * 
 * Untuk semua admin (master dan regional):
 * - /admin/dashboard/{region} (regional dashboard)
 * - /admin/input-lapangan/* (manage lapangan, slider, event)
 */
Route::middleware(['auth.admin'])->prefix('admin')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        // Master Admin Dashboard - lihat semua region
        // Middleware: check role master
        Route::get('/dashboard', 'homeAdmin')
            ->middleware('admin.role:master')
            ->name('admin.dashboard');
        
        // Dashboard dinamis per region - bisa akses sesuai region user
        // Middleware: check region access (master bisa semua, regional hanya region mereka)
        Route::get('/dashboard/{region}', 'adminDashboard')
            ->middleware('region.access')
            ->name('admin.dashboard.region');
        
        // API endpoint untuk ambil data region
        Route::get('/api/region/{region}', 'getRegionData')->name('admin.region.data');
        Route::get('/api/regions', 'getRegions')->name('admin.regions.list');
    });
    
    /**
     * ROUTE INPUT LAPANGAN - LAPANGAN, SLIDER, EVENT
     * Accessible untuk master dan regional admin sesuai region
     */
    Route::controller(InputLapanganController::class)->prefix('input-lapangan')->group(function () {
        // Lapangan
        Route::get('/inputLapangan', 'inputLapangan')->name('inputLapangan.Lapangan');
        Route::get('/daftarLapangan', 'daftarLapangan')->name('lapangan.daftar.Lapangan');
        Route::post('/inputLapangan', 'store')->name('lapangan.store');
        Route::get('/viewLapangan', 'viewLapangan')->name('lapangan.view');
        Route::get('/editLapangan/{id}', 'editLapangan')->name('lapangan.edit');
        Route::put('/updateLapangan/{id}', 'update')->name('lapangan.update');
        Route::delete('/deleteLapangan/{id}', 'destroy')->name('lapangan.destroy');
        
        // Slider
        Route::get('/slider', 'slider')->name('lapangan.slider');
        Route::post('/slider', 'storeSlider')->name('lapangan.slider.store');
        Route::get('/slider/{id}/edit', 'editSlider')->name('lapangan.slider.edit');
        Route::put('/slider/{id}', 'updateSlider')->name('lapangan.slider.update');
        Route::delete('/slider/{id}', 'destroySlider')->name('lapangan.slider.destroy');
        
        // Event
        Route::get('/event', 'event')->name('lapangan.event');
        Route::post('/event', 'storeEvent')->name('lapangan.event.store');
        Route::get('/event/{id}/edit', 'editEvent')->name('lapangan.event.edit');
        Route::put('/event/{id}', 'updateEvent')->name('lapangan.event.update');
        Route::delete('/event/{id}', 'destroyEvent')->name('lapangan.event.destroy');
    });
});

/**
 * ROUTE CUSTOMER
 */
Route::middleware('auth')->group(function () {
    Route::controller(CostumerController::class)->group(function () {
        Route::get('/dashboard/padang', 'padang')->name('costumers.dashboard.padang');
        Route::get('/dashboard/sijunjung', 'sijunjung')->name('costumers.dashboard.sijunjung');
        Route::get('/dashboard/bukittinggi', 'bukittinggi')->name('costumers.dashboard.bukittinggi');
    });
});

/**
 * ROUTE BOOKING
 */
Route::controller(BokingController::class)->prefix('boking')->group(function () {
    Route::get('/', 'bookingForm')->name('boking.form');
    Route::get('/bokingForm', 'bookingForm')->name('boking.form');
    Route::post('/bokingForm', 'store')->name('boking.store');
    Route::get('/payment', 'payment')->name('show.payment');
});

/**
 * ROUTE API - AVAILABILITY
 */
Route::controller(\App\Http\Controllers\AvailabilityController::class)->prefix('api')->group(function () {
    Route::get('/availability/hours', 'getAvailableHours')->name('api.availability.hours');
    Route::get('/availability/booked-slots', 'getBookedSlots')->name('api.availability.booked-slots');
    Route::get('/availability/booked-dates', 'getBookedDates')->name('api.availability.booked-dates');
});
