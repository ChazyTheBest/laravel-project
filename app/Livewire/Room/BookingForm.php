<?php declare(strict_types=1);

namespace App\Livewire\Room;

use App\Http\Requests\CheckBookingRequest;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class BookingForm extends Component
{
    public Room $room;
    public string $check_in_date;
    public string $check_out_date;

    public function mount(Room $room): void
    {
        $this->room = $room;
        $this->check_in_date = '';
        $this->check_out_date = '';
    }

    public function render()
    {
        $bookingMinDates = $this->getBookingMinDates();

        return view('room.booking-form', [
            'room' => $this->room,
            'checkInDateMin' => $bookingMinDates[0],
            'checkOutDateMin' => $bookingMinDates[1],
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function submitForm(): void
    {
        $this->validateCheckBookingRequest();

        // Emit event with booking dates
        $this->dispatch('bookingDatesSelected', [
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date
        ]);
    }

    private function getBookingMinDates(): array
    {
        $now = Carbon::now();
        $today = $now->toDateString();

        $tomorrow = $now->copy()->addDay()->toDateString();
        $dayAfterTomorrow = $now->copy()->addDays(2)->toDateString();

        $request = new CheckBookingRequest;
        $rules = $request->rules();

        $checkInDateMin = null;
        $checkOutDateMin = $dayAfterTomorrow;

        if (in_array('after_or_equal:today', $rules['check_in_date'])) {
            $checkInDateMin = $today;
        } elseif (in_array('after:today', $rules['check_in_date'])) {
            $checkInDateMin = $tomorrow;
        }

        return [$checkInDateMin, $checkOutDateMin];
    }

    /**
     * @throws ValidationException
     */
    private function validateCheckBookingRequest(): void
    {
        $request = new CheckBookingRequest();

        $data = [
            'room_id' => $this->room->id,
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
        ];

        Validator::make($data, $request->rules(), $request->messages())->validate();
    }
}
