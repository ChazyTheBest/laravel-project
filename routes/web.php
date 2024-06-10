<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use App\Livewire\BookingComponent;
use App\Livewire\ProfileComponent;
use App\Livewire\RoomComponent;
use App\Livewire\UserComponent;
use App\Models\Profile;
use App\Models\Room;
use App\Models\User;

Route::get('/', fn () => view('dashboard'))->name('dashboard');

Route::get('/room', [RoomController::class, 'index'])
    ->name('room.index');

Route::get('/room/{room}', [RoomController::class, 'show'])
    ->name('room.show');

Route::middleware('auth')->group(function () {
    Route::get('/profiles', ProfileComponent::class)
        ->name('profiles')
        /*->middleware([
            'can:viewAny,' . Profile::class,   // Permission to view any profile
            'can:view,profile',                // Permission to view a specific profile (if relevant)
            'can:create,' . Profile::class,    // Permission to create a profile
            'can:update,profile',              // Permission to update a specific profile
            'can:delete,profile',              // Permission to delete a specific profile
        ])*/;

    Route::get('/rooms', RoomComponent::class)
        ->name('rooms')
        /*->middleware([
            'can:viewAny,' . Room::class,   // Permission to view any room
            'can:view,room',                // Permission to view a specific room (if relevant)
            'can:create,' . Room::class,    // Permission to create a room
            'can:update,room',              // Permission to update a specific room
            'can:delete,room',              // Permission to delete a specific room
        ])*/;

    Route::get('/users', UserComponent::class)
        ->name('users')
        /*->middleware([
            'can:viewAny,' . User::class,   // Permission to view any user
            'can:view,user',                // Permission to view a specific user (if relevant)
            'can:create,' . User::class,    // Permission to create a user
            'can:update,user',              // Permission to update a specific user
            'can:delete,user',              // Permission to delete a specific user
        ])*/;

    Route::post('/booking/room/{room}', fn (Room $room) => view('booking.info', [
        'room' => $room
    ]))->name('booking.info');

    Route::get('/payment/mockup', [PaymentController::class, 'mockup'])
        ->name('payment.mockup');

    Route::post('/payment/callback/{payment}', [PaymentController::class, 'callback'])
        ->name('payment.callback');
});
