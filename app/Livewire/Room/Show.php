<?php declare(strict_types=1);

namespace App\Livewire\Room;

use App\Models\Room;
use Livewire\Component;

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
        $this->bookingDates = $bookingDates;
        $this->step = 'booking-create';
    }
}
