<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\BusController;
use App\Http\Controllers\Api\RouteController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\BusScheduleController;
use App\Http\Controllers\Api\PaymentController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::post('/users', [UserController::class, 'store']);
Route::post('/upload-users', [UserController::class, 'uploadUsers']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::patch('/users/{user}/status', [UserController::class, 'updateStatus']);


Route::post('/bookings', [BookingController::class, 'store']);
Route::patch('/bookings/{id}/status', [BookingController::class, 'updatePaymentStatus']);
Route::get('/schedules/{scheduleId}/seats', [BookingController::class, 'getAvailableSeats']);
Route::patch('/bookings/{id}/update-payment-status', [BookingController::class, 'updatePaymentStatus']);


Route::post('/buses', [BusController::class, 'store']);
Route::delete('/buses/{id}', [BusController::class, 'destroy']);

Route::post('/routes', [RouteController::class, 'store']);
Route::delete('/routes/{id}', [RouteController::class, 'destroy']);

Route::post('/schedules', [ScheduleController::class, 'store']);
Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy']);

Route::post('/bus-schedules', [BusScheduleController::class, 'store']);
Route::delete('/bus-schedules/{id}', [BusScheduleController::class, 'destroy']);

Route::post('/payments', [PaymentController::class, 'store']);