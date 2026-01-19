<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BokingController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InputLapanganController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


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


Route::middleware(['auth.admin'])->prefix('admin')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        // Master Admin Dashboard - lihat semua region
        // Middleware: check role master
        Route::get('/dashboard', 'homeAdmin')
            ->middleware('admin.role:master')
            ->name('admin.dashboard');
        Route::get('/dashboard/{region}', 'adminDashboard')
            ->middleware('region.access')
            ->name('admin.dashboard.region');
        
        // API endpoint untuk ambil data region
        Route::get('/api/region/{region}', 'getRegionData')->name('admin.region.data');
        Route::get('/api/regions', 'getRegions')->name('admin.regions.list');
    });

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

Route::middleware('auth')->group(function () {
    Route::controller(CostumerController::class)->group(function () {
        Route::get('/dashboard/padang', 'padang')->name('costumers.dashboard.padang');
        Route::get('/dashboard/sijunjung', 'sijunjung')->name('costumers.dashboard.sijunjung');
        Route::get('/dashboard/bukittinggi', 'bukittinggi')->name('costumers.dashboard.bukittinggi');
    });
});


Route::controller(BokingController::class)->prefix('boking')->group(function () {
    Route::get('/', 'bookingForm')->name('boking.form');
    Route::get('/bokingForm', 'bookingForm')->name('boking.form');
    Route::post('/bokingForm', 'store')->name('boking.store');
    Route::get('/payment', 'payment')->name('show.payment');
});

// Payment success page
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');


Route::controller(MidtransController::class)->prefix('midtrans')->group(function () {
    Route::get('/checkout/{order}', 'checkout')->name('checkout');
    Route::get('/debug', 'debugConfig')->name('midtrans.debug');
    Route::post('/token/{order}', 'token')->name('midtrans.token');
    Route::post('/notification', 'notification')->name('midtrans.notification');
    
    // Testing endpoints (development only)
    Route::get('/test-webhook', 'testWebhook')->name('midtrans.test-webhook');
    Route::get('/manual-confirm', 'manualConfirm')->name('midtrans.manual-confirm');
});

// Availability endpoints
Route::controller(AvailabilityController::class)->prefix('api')->group(function () {
    Route::get('/available-hours', 'getAvailableHours')->name('api.available-hours');
    Route::get('/booked-slots', 'getBookedSlots')->name('api.booked-slots');
});

// Payment API endpoint
Route::post('/api/payment-token', [MidtransController::class, 'token'])->name('api.payment-token');

// webhook dari Midtrans (sebaiknya di routes/api.php juga boleh)
