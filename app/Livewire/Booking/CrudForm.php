<?php declare(strict_types=1);

namespace App\Livewire\Booking;

use App\Models\Booking;
use App\Http\Requests\UpdateBookingRequest;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CrudForm extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'check_in_date';
    public $sortDirection = 'asc';
    public $isBookingEditOpen = false;
    public $isBookingDeleteOpen = false;
    public $currentBooking = null;

    protected $queryString = ['sortField', 'sortDirection'];

    // Booking Fields
    public int $profile_id;
    public int $room_id;
    public string $check_in_date;
    public string $check_out_date;

    public function mount()
    {
        $this->clearFormAttributes();
    }

    public function clearFormAttributes()
    {
        $this->profile_id = 0;
        $this->room_id = 0;
        $this->check_in_date = '';
        $this->check_out_date = '';
    }

    private function getBookingData()
    {
        return [
            'profile_id' => $this->profile_id,
            'room_id' => $this->room_id,
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
        ];
    }

    protected function rules(): array
    {
        $request = new UpdateBookingRequest();
        $request->merge([
            'profile_id' => $this->profile_id,
            'room_id' => $this->room_id,
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
        ]);
        return $request->rules();
    }

    public function messages(): array
    {
        return (new UpdateBookingRequest())->messages();
    }

    public function authorizeFormRequest(): bool
    {
        $request = new UpdateBookingRequest();
        $request->merge([
            'profile_id' => $this->profile_id,
        ]);
        return $request->authorize();
    }

    public function openBookingEdit(string $id)
    {
        $this->currentBooking = Booking::find($id);
        if ($this->currentBooking) {
            $this->profile_id = $this->currentBooking->profile_id;
            $this->room_id = $this->currentBooking->room_id;
            $this->check_in_date = $this->currentBooking->check_in_date;
            $this->check_out_date = $this->currentBooking->check_out_date;
            $this->isBookingEditOpen = true;
        }
    }

    public function closeBookingEdit()
    {
        $this->isBookingEditOpen = false;
        $this->currentBooking = null;
        $this->clearFormAttributes();
        $this->resetErrorBag();
    }

    public function updateBooking()
    {
        $this->authorize('update', $this->currentBooking);
        $this->validate();
        $this->authorizeFormRequest();
        try {
            if ($this->currentBooking->exists()) {
                $this->currentBooking->update($this->getBookingData());
            }
            $this->closeBookingEdit();
            session()->flash('success', 'Booking updated successfully!');
        } catch (\Exception $ex) {
            session()->flash('error', 'Something goes wrong!!');
        }
    }

    public function openBookingDelete(string $id)
    {
        $this->isBookingDeleteOpen = true;
        $this->currentBooking = Booking::find($id);
    }

    public function closeBookingDelete()
    {
        $this->isBookingDeleteOpen = false;
    }

    public function destroyBooking()
    {
        $this->authorize('delete', $this->currentBooking);
        try {
            if ($this->currentBooking->exists()) {
                $this->currentBooking->delete();
            }
            $this->closeBookingDelete();
            session()->flash('success', 'Booking deleted successfully!');
        } catch (\Exception $ex) {
            session()->flash('error', 'Something goes wrong!!');
        }
    }

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field ?
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc' : 'asc';
        $this->sortField = $field;

    }

    public function render()
    {
        return view('livewire.bookings', [
            'bookings' => Booking::orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
        ])->layout('layouts.app');
    }
}
