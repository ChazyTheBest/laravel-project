<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve the authenticated user
        $user = $request->user();

        // Fetch the user's bookings with eager loading of profile and room
        $bookings = $user->bookings()->with('profile', 'room')->get();

        // Pass the bookings data to the view
        return view('booking.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        return view('booking.show', [
            'booking' => $booking->load('profile', 'room')
        ]);
    }
}
