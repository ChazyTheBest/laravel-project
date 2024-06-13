<?php declare(strict_types=1);

namespace App\Livewire\Payment;

use App\Models\Payment;
use App\Enums\PaymentStatus;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'status';
    public $sortDirection = 'asc';
    public $isPaymentCreateOpen = false;
    public $isPaymentEditOpen = false;
    public $isPaymentDeleteOpen = false;
    public $currentPayment = null;

    protected $queryString = ['sortField', 'sortDirection'];

    // Payment Fields
    public int $booking_id;
    public PaymentStatus $status;
    public ?array $response_data;
    public ?string $response_data_form;

    public function mount(): void
    {
        $this->clearFormAttributes();
    }

    public function clearFormAttributes(): void
    {
        $this->booking_id = 0;
        $this->status = PaymentStatus::PENDING;
        $this->response_data = [];
    }

    private function getPaymentData(): array
    {
        return [
            'booking_id' => $this->booking_id,
            'status' => $this->status,
            'response_data' => $this->response_data,
        ];
    }

    public function openPaymentEdit(string $id): void
    {
        $this->currentPayment = Payment::find($id);
        if ($this->currentPayment) {
            $this->booking_id = $this->currentPayment->booking_id;
            $this->status = $this->currentPayment->status;
            $this->response_data_form = json_encode($this->currentPayment->response_data, JSON_PRETTY_PRINT);
            $this->isPaymentEditOpen = true;
        }
    }

    public function closePaymentEdit(): void
    {
        $this->isPaymentEditOpen = false;
        $this->currentPayment = null;
        $this->clearFormAttributes();
        $this->resetErrorBag();
    }

    public function sortBy($field): void
    {
        $this->sortDirection = $this->sortField === $field ?
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc' : 'asc';
        $this->sortField = $field;
    }

    public function render()
    {
        return view('livewire.payments', [
            'payments' => Payment::orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
        ])->layout('layouts.app');
    }
}
