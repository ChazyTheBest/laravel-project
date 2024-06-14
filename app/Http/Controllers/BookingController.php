<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Fetch the user's bookings with eager loading of profile and room
        $bookings = $user->bookings()->with('profile', 'room')->get();

        return view('booking.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load('profile', 'room');
        $room = $booking->room;

        return view('booking.show', [
            'booking' => $booking,
            'isAvailable' => $room->isAvailable($booking->check_in_date, $booking->check_out_date)
        ]);
    }
}
