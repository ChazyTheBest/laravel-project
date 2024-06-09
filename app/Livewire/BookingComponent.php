<?php declare(strict_types=1);

namespace App\Livewire;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\StoreBillingInfoRequest;
use Livewire\Component;

class BookingComponent extends Component
{
    public function store(StoreBookingRequest $bookingRequest, StoreBillingInfoRequest $billingInfoRequest)
    {
        // Authorize the creation of a Booking
        $this->authorize('create', Booking::class);

        // Authorize the creation of a Payment
        $this->authorize('create', Payment::class);

        // Authorize the creation of BillingInfo
        $this->authorize('create', BillingInfo::class);
    }
}
