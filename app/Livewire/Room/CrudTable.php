<?php declare(strict_types=1);

namespace App\Livewire\Room;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
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

    public ?Room $currentModel = null;

    // Room Fields
    public ?int $number;
    public ?int $capacity;
    public ?int $beds;
    public string $name;
    public string $description;
    public string $price_per_night;

    public function mount(): void
    {
        $this->clearFormAttributes();
    }

    public function render()
    {
        return view('livewire.room.crud-table', [
            'rooms' => Room::orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
        ]);
    }

    public function openCreateModal()
    {
        $this->clearFormAttributes();
        $this->openModal(
            'create',
            __('Create Room'),
            'components.room.form-fields',
            'storeRoom',
            'closeModal',
            'Create'
        );
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function storeRoom(): void
    {
        $this->authorize('create', Room::class);
        $this->validateAndAuthorizeRoomRequest();
        Room::create($this->getRoomData());
        $this->closeModal();
    }

    public function openEditModal(string $id): void
    {
        $this->currentModel = Room::find($id);
        if ($this->currentModel) {
            $this->number = $this->currentModel->number;
            $this->capacity = $this->currentModel->capacity;
            $this->beds = $this->currentModel->beds;
            $this->name = $this->currentModel->name;
            $this->description = $this->currentModel->description;
            $this->price_per_night = $this->currentModel->price_per_night;
        }

        $this->openModal(
            'edit',
            __('Edit Room'),
            'components.room.form-fields',
            'updateRoom',
            'closeModal',
            'Edit'
        );
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function updateRoom(): void
    {
        $this->authorize('update', $this->currentModel);
        $this->validateAndAuthorizeRoomRequest();

        if ($this->currentModel->exists()) {
            $this->currentModel->update($this->getRoomData());
        }

        $this->closeModal();
    }

    public function openDeleteModal(string $id): void
    {
        $this->currentModel = Room::find($id);
        $this->openModal(
            'delete',
            __('Delete Room'),
            __('Are you sure you want to delete this room?'),
            'destroyRoom',
            'closeModal',
            'Delete'
        );
    }

    /**
     * @throws AuthorizationException
     */
    public function destroyRoom(): void
    {
        $this->authorize('delete', $this->currentModel);

        if ($this->currentModel->exists()) {
            $this->currentModel->delete();
        }

        $this->closeModal();
    }

    protected function clearFormAttributes(): void
    {
        $this->number = null;
        $this->capacity = null;
        $this->beds = null;
        $this->name = '';
        $this->description = '';
        $this->price_per_night = '';
    }

    private function getRoomData(): array
    {
        return [
            'id' => $this->currentModel->id ?? null,
            'number' => $this->number,
            'capacity' => $this->capacity,
            'beds' => $this->beds,
            'name' => $this->name,
            'description' => $this->description,
            'price_per_night' => $this->price_per_night,
        ];
    }

    /**
     * @throws ValidationException
     * @throws AuthorizationException
     */
    private function validateAndAuthorizeRoomRequest(): void
    {
        $request = $this->currentModel
            ? new UpdateRoomRequest()
            : new StoreRoomRequest();

        $data = $this->getRoomData();

        $request->merge($data);

        Validator::make($data, $request->rules(), $request->messages())->validate();

        if (!$request->authorize()) {
            $request->failedAuthorization();
        }
    }
}
