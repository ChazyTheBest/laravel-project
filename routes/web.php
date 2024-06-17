<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use App\Livewire\Room\Show;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Profile;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('dashboard'))->name('dashboard');

Route::get('/rooms', [RoomController::class, 'index'])->name('room.index');
Route::get('/room/{room}', Show::class)->name('room.show');

Route::get('/booking/confirmed', fn () => view('booking.confirmed'))->name('booking.confirmed');
Route::get('/booking/failed', fn () => view('booking.failed'))->name('booking.failed');

Route::middleware('auth')->group(function () {
    Route::get('/admin/bookings', fn () => view('livewire.admin-booking'))
        ->name('admin.booking')
        ->middleware([
            'can:viewAny,' . Booking::class,   // Permission to view any booking
            'can:create,' . Booking::class,    // Permission to create a booking
        ]);
    Route::get('/admin/payments', fn () => view('livewire.admin-payment'))
        ->name('admin.payment')
        ->middleware([
            'can:viewAny,' . Payment::class,   // Permission to view any payment
            'can:create,' . Payment::class,    // Permission to create a payment
        ]);
    Route::get('/profiles', fn () => view('livewire.admin-profile'))
        ->name('profile.index')
        ->middleware([
            'can:viewAny,' . Profile::class,   // Permission to view any profile
            'can:create,' . Profile::class,    // Permission to create a profile
        ]);
    Route::get('/admin/rooms', fn () => view('livewire.admin-room'))
        ->name('admin.room')
        ->middleware([
            'can:viewAny,' . Room::class,   // Permission to view any room
            'can:create,' . Room::class,    // Permission to create a room
        ]);
    Route::get('/admin/users', fn () => view('livewire.admin-user'))
        ->name('admin.user')
        ->middleware([
            'can:viewAny,' . User::class,   // Permission to view any user
            'can:create,' . User::class,    // Permission to create a user
        ]);

    Route::get('/bookings', [BookingController::class, 'index'])
        ->name('booking.index')
        ->middleware('can:viewAny,' . Booking::class);

    Route::get('/booking/{booking}', [BookingController::class, 'show'])
        ->name('booking.show')
        ->middleware('can:view,booking');

    Route::post('/booking/room/{room}', fn (Room $room) => view('booking.info', [
        'room' => $room
    ]))->name('booking.info')
    ->middleware('can:create,' . Booking::class);

    Route::get('/payment/mockup/{payment}', [PaymentController::class, 'mockup'])
        ->name('payment.mockup');

    Route::post('/payment/callback/{payment}', [PaymentController::class, 'callback'])
        ->name('payment.callback');
});
