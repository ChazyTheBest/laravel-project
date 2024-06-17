<?php declare(strict_types=1);

namespace App\Livewire\Booking;

use App\Models\Booking;
use App\Http\Requests\UpdateBookingRequest;
use App\Traits\WithModelState;
use App\Traits\WithTable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class CrudTable extends Component
{
    use AuthorizesRequests;
    use WithModelState;
    use WithPagination;
    use WithTable;

    public ?Booking $currentModel = null;

    // Booking Fields
    public ?int $profile_id;
    public ?int $room_id;
    public int $status;
    public string $check_in_date;
    public string $check_out_date;

    public function mount(): void
    {
        $this->sortField = 'check_in_date';
        $this->clearFormAttributes();
    }

    public function render()
    {
        return view('livewire.booking.crud-table', [
            'bookings' => Booking::orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
        ]);
    }

    public function openEditModal(string $id): void
    {
        $this->currentModel = Booking::find($id);
        if ($this->currentModel) {
            $this->profile_id = $this->currentModel->profile_id;
            $this->room_id = $this->currentModel->room_id;
            $this->status = $this->currentModel->status->value;
            $this->check_in_date = $this->currentModel->check_in_date;
            $this->check_out_date = $this->currentModel->check_out_date;
        }

        $this->openModal(
            'update',
            __('Update Booking'),
            'components.booking.form-fields',
            'updateBooking',
            'closeModal',
            'Update'
        );
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function updateBooking(): void
    {
        $this->authorize('update', $this->currentModel);
        $this->validateAndAuthorizeBookingRequest();

        if ($this->currentModel->exists()) {
            $this->currentModel->update($this->getBookingData());
        }

        $this->closeModal();
    }

    public function openDeleteModal(string $id): void
    {
        $this->currentModel = Booking::find($id);
        $this->openModal(
            'delete',
            __('Delete Booking'),
            __('Are you sure you want to delete this booking?'),
            'destroyBooking',
            'closeModal',
            'Delete'
        );
    }

    /**
     * @throws AuthorizationException
     */
    public function destroyBooking(): void
    {
        $this->authorize('delete', $this->currentModel);

        if ($this->currentModel->exists()) {
            $this->currentModel->delete();
        }

        $this->closeModal();
    }

    private function clearFormAttributes(): void
    {
        $this->profile_id = null;
        $this->room_id = null;
        $this->status = 0;
        $this->check_in_date = '';
        $this->check_out_date = '';
    }

    private function getBookingData(): array
    {
        return [
            'profile_id' => $this->profile_id,
            'room_id' => $this->room_id,
            'status' => $this->status,
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
        ];
    }

    /**
     * @throws ValidationException
     * @throws AuthorizationException
     */
    private function validateAndAuthorizeBookingRequest(): void
    {
        $request = new UpdateBookingRequest();

        $data = $this->getBookingData();

        $request->merge($data);

        Validator::make($data, $request->rules(), $request->messages())->validate();

        if (!$request->authorize()) {
            $request->failedAuthorization();
        }
    }
}
