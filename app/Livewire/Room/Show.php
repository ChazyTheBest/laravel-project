<?php declare(strict_types=1);

namespace App\Livewire\Room;

use App\Models\Room;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{
    public Room $room;
    public string $step = 'room-show'; // Default step
    public array $bookingDates;

    protected $listeners = [
        'bookingDatesSelected' => 'handleBookingDatesSelected'
    ];

    public function mount(Room $room)
    {
        $this->room = $room;
    }

    public function render()
    {
        return view('room.show', [
            'room' => $this->room,
        ])->layout('layouts.app');
    }

    public function handleBookingDatesSelected($bookingDates)
    {
        if (!Auth::check()) {
            session(['url.intended' => route('room.show', $this->room->id)]);
            return redirect()->route('login');
        }

        $this->bookingDates = $bookingDates;
        $this->step = 'booking-create';
    }
}
