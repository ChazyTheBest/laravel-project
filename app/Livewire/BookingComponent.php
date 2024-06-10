<?php declare(strict_types=1);

namespace App\Livewire;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\StoreBillingInfoRequest;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BookingComponent extends Component
{
    public Room $room;
    public object $profiles;
    public User $user;

    // Booking required data
    public int $profile_id;
    public int $room_id;
    public string $checkInDate;
    public string $checkOutDate;

    // The payment method to be used
    public int $payment_method;

    // BillingInfo required data
    public string $address;
    public string $city;
    public string $state;
    public string $postal_code;
    public string $country;

    public function mount(Room $room)
    {
        $this->room = $room;
        $this->user = Auth::user();
        $this->profiles = $this->user->profiles()->get();

        if ($this->profiles->isNotEmpty()) {
            $this->profile_id = $this->profiles->first()->id;
            $profile = $this->profiles->first();
            $this->address = $profile->address;
            $this->city = $profile->city;
            $this->state = $profile->state;
            $this->postal_code = $profile->postal_code;
            $this->country = $profile->country;
        }

        $this->checkInDate = request()->input('check_in_date');
        $this->checkOutDate = request()->input('check_out_date');

        return $this->render();
    }

    public function render()
    {
        return view('livewire.booking', [
            'profiles' => $this->profiles,
            'room' => $this->room,
            'checkInDate' => $this->checkInDate,
            'checkOutDate' => $this->checkOutDate,
        ]);
    }

    public function rules()
    {
        return [
            ...(new StoreBookingRequest())->rules(),
            ...(new StoreBillingInfoRequest())->rules(),
        ];
    }

    public function messages()
    {
        return [
            ...(new StoreBookingRequest())->messages(),
            ...(new StoreBillingInfoRequest())->messages(),
        ];
    }

    public function book()
    {
        $this->authorize('create', Booking::class);
        $this->authorize('create', Payment::class);
        $this->authorize('create', BillingInfo::class);

        $this->validate();

        $profile = $this->user->profiles()->findOrFail($this->profile_id);

        // Rollback on insert error
        DB::transaction(function () use ($profile) {
            // Step 1: Create booking
            $booking = $profile->bookings()->create([
                'room_id' => $this->room->id,
                'check_in_date' => $this->checkInDate,
                'check_out_date' => $this->checkOutDate,
            ]);

            // Step 2: Create payment
            $payment = $booking->payment()->create();

            // Step 3: Create billing info
            $billing_info = $payment->billingInfo()->create([
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
                'country' => $this->country,
                'payment_id' => $payment->id,
            ]);

            return redirect()->route('payment.mockup');
        });
    }
}
