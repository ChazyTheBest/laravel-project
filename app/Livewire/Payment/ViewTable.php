<?php declare(strict_types=1);

namespace App\Livewire\Payment;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Traits\WithModelState;
use App\Traits\WithTable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ViewTable extends Component
{
    use AuthorizesRequests;
    use WithModelState;
    use WithPagination;
    use WithTable;

    public ?Payment $currentModel = null;

    // Payment Fields
    public int $booking_id;
    public PaymentStatus $status;
    public ?array $response_data;
    public ?string $response_data_form;

    public function mount(): void
    {
        $this->sortField = 'status';
        $this->clearFormAttributes();
    }

    public function render()
    {
        return view('livewire.payment.view-table', [
            'payments' => Payment::orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function openViewModal(string $id): void
    {
        $this->clearFormAttributes();
        $this->currentModel = Payment::find($id);
        $this->authorize('view', $this->currentModel);
        if ($this->currentModel) {
            $this->booking_id = $this->currentModel->booking_id;
            $this->status = $this->currentModel->status;
            $this->response_data_form = json_encode($this->currentModel->response_data, JSON_PRETTY_PRINT);
        }

        $this->openModal(
            'view',
            __('View Payment'),
            'components.payment.form-fields',
            'closeModal',
            'closeModal',
            'View'
        );
    }

    protected function clearFormAttributes(): void
    {
        $this->booking_id = 0;
        $this->status = PaymentStatus::PENDING;
        $this->response_data = [];
    }
}
