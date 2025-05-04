<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\BusController;
use App\Http\Controllers\Api\RouteController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\BusScheduleController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/bus', [BusController::class, 'index'])->name('bus.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/route', [RouteController::class, 'index'])->name('route.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');


    Route::get('/bus_schedule', [BusScheduleController::class, 'index'])->name('bus_schedule.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
