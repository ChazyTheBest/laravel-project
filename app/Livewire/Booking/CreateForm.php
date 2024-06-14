<?php declare(strict_types=1);

namespace App\Livewire\Booking;

use App\Http\Requests\CheckPaymentMethodRequest;
use App\Http\Requests\StoreBillingInfoRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Profile;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class CreateForm extends Component
{
    public Room $room;
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

    public function mount(Room $room, array $bookingDates)
    {
        $this->room = $room;
        $this->profiles = Auth::user()->profiles()->get();
        $this->payment_method = 0;

        if ($this->profiles->isNotEmpty()) {
            $profile = $this->profiles->first();
            $this->profile_id = $profile->id;
            $this->address = $profile->address;
            $this->city = $profile->city;
            $this->state = $profile->state;
            $this->postal_code = $profile->postal_code;
            $this->country = $profile->country;
        }

        // hidden input elements for re-validation
        $this->room_id = $room->id;
        $this->check_in_date = $bookingDates['check_in_date'];
        $this->check_out_date = $bookingDates['check_out_date'];
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

    private function authorizeActions(): void
    {
        $this->authorize('create', Booking::class);
        $this->authorize('create', Payment::class);
        $this->authorize('create', BillingInfo::class);
    }

    private function validateAndAuthorizeFormRequests(): void
    {
        $storeBookingRequest = new StoreBookingRequest();
        $checkPaymentMethodRequest = new CheckPaymentMethodRequest();
        $storeBillingInfoRequest = new StoreBillingInfoRequest();

        $bookingRequestData = [
            'profile_id' => $this->profile_id,
            'room_id' => $this->room_id,
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
        ];

        $paymentMethodRequestData = [
            'payment_method' => $this->payment_method,
        ];

        $billingInfoRequestData = [
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
        ];

        $storeBookingRequest->merge($bookingRequestData);
        //$checkPaymentMethodRequest->merge($paymentMethodRequestData);
        $storeBillingInfoRequest->merge($billingInfoRequestData);

        $data = [
            ...$bookingRequestData,
            ...$paymentMethodRequestData,
            ...$billingInfoRequestData,
        ];

        $rules = [
            ...$storeBookingRequest->rules(),
            ...$checkPaymentMethodRequest->rules(),
            ...$storeBillingInfoRequest->rules(),
        ];

        $messages = [
            ...$storeBookingRequest->messages(),
            ...$checkPaymentMethodRequest->messages(),
            ...$storeBillingInfoRequest->messages(),
        ];

        Validator::make($data, $rules, $messages)->validate();

        if (!$storeBookingRequest->authorize()) {
            $storeBookingRequest->failedAuthorization();
        }

        if (!$storeBillingInfoRequest->authorize()) {
            $storeBillingInfoRequest->failedAuthorization();
        }
    }

    public function book()
    {
        $this->authorizeActions();

        $this->validateAndAuthorizeFormRequests();

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
