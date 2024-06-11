<?php declare(strict_types=1);

namespace App\Livewire\Booking;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\StoreBillingInfoRequest;
use App\Models\Profile;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateForm extends Component
{
    public Room $room;
    public User $user;
    public object $profiles;

    // Booking required data
    public int $profile_id;
    public int $room_id;
    public string $check_in_date;
    public string $check_out_date;

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

        // hidden input elements for re-validation
        $this->room_id = $room->id;
        $this->check_in_date = request()->input('check_in_date');
        $this->check_out_date = request()->input('check_out_date');

        return $this->render();
    }

    public function render()
    {
        return view('booking.create-form', [
            'profiles' => $this->profiles,
            'room' => $this->room,
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
        ]);
    }

    public function rules()
    {
        return [
            ...(new StoreBookingRequest())->rules(),
            ...(new StoreBillingInfoRequest())->rules(),
            'payment_method' => 'required|numeric|in:1,2' // todo abstract payment method names
        ];
    }

    public function messages()
    {
        return [
            ...(new StoreBookingRequest())->messages(),
            ...(new StoreBillingInfoRequest())->messages(),
            'payment_method.required' => 'The payment method is required. Please select one.',
            'payment_method.numeric' => 'Please select a valid payment method.',
            'payment_method.in' => 'The selected payment method is invalid.',
        ];
    }

    public function authorizeCreateBookingRequest(): bool
    {
        return (new StoreBookingRequest())->authorize($this->profile_id.'');
    }

    public function authorizeCreateBillingInfoRequest(): bool
    {
        return (new StoreBillingInfoRequest())->authorize();
    }

    public function book()
    {
        $this->authorize('create', Booking::class);
        $this->authorize('create', Payment::class);
        $this->authorize('create', BillingInfo::class);

        $this->authorizeCreateBookingRequest();
        $this->authorizeCreateBillingInfoRequest();

        $this->validate();

        // Rollback on insert error
        DB::transaction(function () {
            // Step 1: Create booking
            $booking = Profile::find($this->profile_id)->bookings()->create([
                'room_id' => $this->room->id,
                'check_in_date' => $this->check_in_date,
                'check_out_date' => $this->check_out_date,
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

            return redirect()->route('payment.mockup', [
                'payment' => $payment
            ]);
        });
    }
}
