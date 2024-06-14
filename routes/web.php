<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use App\Livewire\Booking\CrudForm as BookingCrudForm;
use App\Livewire\Payment\Table as PaymentTable;
use App\Livewire\Profile\CrudForm as ProfileCrudForm;
use App\Livewire\Room\CrudForm as RoomCrudForm;
use App\Livewire\User\CrudForm as UserCrudForm;
use App\Livewire\Room\Show;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Profile;
use App\Models\Room;
use App\Models\User;

Route::get('/', fn () => view('dashboard'))->name('dashboard');

Route::get('/rooms', [RoomController::class, 'index'])->name('room.index');
Route::get('/room/{room}', Show::class)->name('room.show');

Route::get('/booking/confirmed', fn () => view('booking.confirmed'))->name('booking.confirmed');
Route::get('/booking/failed', fn () => view('booking.failed'))->name('booking.failed');

Route::middleware('auth')->group(function () {
    Route::get('/admin/bookings', BookingCrudForm::class)
        ->name('admin.booking')
        ->middleware([
            'can:viewAny,' . Booking::class,   // Permission to view any booking
            'can:create,' . Booking::class,    // Permission to create a booking
        ]);
    Route::get('/admin/payments', PaymentTable::class)
        ->name('admin.payment')
        ->middleware([
            'can:viewAny,' . Payment::class,   // Permission to view any payment
            'can:create,' . Payment::class,    // Permission to create a payment
        ]);
    Route::get('/profiles', ProfileCrudForm::class)
        ->name('profile.index')
        ->middleware([
            'can:viewAny,' . Profile::class,   // Permission to view any profile
            'can:create,' . Profile::class,    // Permission to create a profile
        ]);
    Route::get('/admin/rooms', RoomCrudForm::class)
        ->name('admin.room')
        ->middleware([
            'can:viewAny,' . Room::class,   // Permission to view any room
            'can:create,' . Room::class,    // Permission to create a room
        ]);
    Route::get('/admin/users', UserCrudForm::class)
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
