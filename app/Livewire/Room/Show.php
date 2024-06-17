<?php declare(strict_types=1);

namespace App\Livewire\Room;

use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public Room $room;
    public string $step = 'room-show'; // Default step
    public array $bookingDates;

    protected $listeners = [
        'bookingDatesSelected' => 'handleBookingDatesSelected'
    ];

    public function mount(Room $room): void
    {
        $this->room = $room;

        if (session()->has('room.booking-form.dates')) {
            $this->bookingDates = session('room.booking-form.dates');
            session()->forget('room.booking-form.dates');
            $this->step = 'booking-create';
        }
    }

    public function render()
    {
        return view('room.show', [
            'room' => $this->room,
        ])->layout('layouts.app');
    }

    public function handleBookingDatesSelected(array $bookingDates):  RedirectResponse|Redirector|null
    {
        if (!Auth::check()) {
            session([
                'url.intended' => route('room.show', $this->room->id),
                'room.booking-form.dates' => $bookingDates
            ]);
            return redirect()->route('login');
        }

        $this->bookingDates = $bookingDates;
        $this->step = 'booking-create';

        return null;
    }
}
