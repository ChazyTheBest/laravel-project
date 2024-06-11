<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use App\Livewire\Booking\CrudForm as BookingCrudForm;
use App\Livewire\Profile\CrudForm as ProfileCrudForm;
use App\Livewire\Room\CrudForm as RoomCrudForm;
use App\Livewire\User\CrudForm as UserCrudForm;
use App\Models\Booking;
use App\Models\Profile;
use App\Models\Room;
use App\Models\User;

Route::get('/', fn () => view('dashboard'))->name('dashboard');

Route::get('/rooms', [RoomController::class, 'index'])
    ->name('room.index');

Route::get('/room/{room}', [RoomController::class, 'show'])
    ->name('room.show');

Route::get('/booking/confirmed', fn () => view('booking.confirmed'))
    ->name('booking.confirmed');

Route::get('/booking/failed', fn () => view('booking.failed'))
    ->name('booking.failed');

Route::middleware('auth')->group(function () {
    Route::get('/admin/users', UserCrudForm::class)
        ->name('admin.user')
        /*->middleware([
            'can:viewAny,' . User::class,   // Permission to view any user
            'can:view,user',                // Permission to view a specific user (if relevant)
            'can:create,' . User::class,    // Permission to create a user
            'can:update,user',              // Permission to update a specific user
            'can:delete,user',              // Permission to delete a specific user
        ])*/;

    Route::get('/profiles', ProfileCrudForm::class)
        ->name('profile.index')
        /*->middleware([
            'can:viewAny,' . Profile::class,   // Permission to view any profile
            'can:view,profile',                // Permission to view a specific profile (if relevant)
            'can:create,' . Profile::class,    // Permission to create a profile
            'can:update,profile',              // Permission to update a specific profile
            'can:delete,profile',              // Permission to delete a specific profile
        ])*/;

    Route::get('/admin/rooms', RoomCrudForm::class)
        ->name('admin.room')
        /*->middleware([
            'can:viewAny,' . Room::class,   // Permission to view any room
            'can:view,room',                // Permission to view a specific room (if relevant)
            'can:create,' . Room::class,    // Permission to create a room
            'can:update,room',              // Permission to update a specific room
            'can:delete,room',              // Permission to delete a specific room
        ])*/;

    Route::get('/admin/bookings', BookingCrudForm::class)
        ->name('admin.booking')
        /*->middleware([
            'can:viewAny,' . Booking::class,   // Permission to view any booking
            'can:view,booking',                // Permission to view a specific booking (if relevant)
            'can:create,' . Booking::class,    // Permission to create a booking
            'can:update,booking',              // Permission to update a specific booking
            'can:delete,booking',              // Permission to delete a specific booking
        ])*/;

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
