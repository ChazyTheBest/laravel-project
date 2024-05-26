<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodAttributeController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePaymentAttributeController;
use App\Http\Controllers\RoomController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::resource('booking', BookingController::class)->withTrashed();
Route::resource('payment', PaymentController::class)->withTrashed();
Route::resource('payment-method-attribute', PaymentMethodAttributeController::class)->withTrashed();
Route::resource('payment-method', PaymentMethodController::class)->withTrashed();
Route::resource('profiletest', ProfileController::class)->withTrashed();
Route::resource('profile-payment-attribute', ProfilePaymentAttributeController::class)->withTrashed();
Route::resource('room', RoomController::class)->withTrashed();
