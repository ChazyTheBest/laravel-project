<?php declare(strict_types=1);

namespace App\Livewire\Room;

use App\Models\Room;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CrudForm extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $isRoomCreateOpen = false;
    public $isRoomEditOpen = false;
    public $isRoomDeleteOpen = false;
    public $currentRoom = null;

    protected $queryString = ['sortField', 'sortDirection'];

    // Room Fields
    public int $number;
    public int $capacity;
    public int $beds;
    public string $name;
    public string $description;
    public string $price_per_night;

    public function mount(): void
    {
        $this->clearFormAttributes();
    }

    public function clearFormAttributes(): void
    {
        $this->number = 0;
        $this->capacity = 0;
        $this->beds = 0;
        $this->name = '';
        $this->description = '';
        $this->price_per_night = '';
    }

    private function getRoomData(): array
    {
        return [
            'number' => $this->number,
            'capacity' => $this->capacity,
            'beds' => $this->beds,
            'name' => $this->name,
            'description' => $this->description,
            'price_per_night' => $this->price_per_night,
        ];
    }

    protected function rules(): array
    {
        return ($this->currentRoom
                    ? new UpdateRoomRequest()
                    : new StoreRoomRequest()
                )->rules(optional($this->currentRoom)->id);
    }

    public function messages(): array
    {
        return ($this->currentRoom
                    ? new UpdateRoomRequest()
                    : new StoreRoomRequest()
                )->messages();
    }

    private function authorizeFormRequest(): bool
    {
        return ($this->currentRoom
                    ? new UpdateRoomRequest()
                    : new StoreRoomRequest()
                )->authorize();
    }

    public function openRoomCreate(): void
    {
        $this->isRoomCreateOpen = true;
    }

    public function closeRoomCreate(): void
    {
        $this->isRoomCreateOpen = false;
        $this->clearFormAttributes();
        $this->resetErrorBag();
    }

    public function storeRoom(): void
    {
        $this->authorize('create', Room::class);
        $this->validate();
        $this->authorizeFormRequest();
        try {
            Room::create($this->getRoomData());
            $this->closeRoomCreate();
            session()->flash('success', 'Room created successfully!');
        } catch (\Exception $ex) {
            die($ex);
            session()->flash('error', 'Something goes wrong!!');
        }
    }

    public function openRoomEdit(string $id): void
    {
        $this->currentRoom = Room::find($id);
        if ($this->currentRoom) {
            $this->number = $this->currentRoom->number;
            $this->capacity = $this->currentRoom->capacity;
            $this->beds = $this->currentRoom->beds;
            $this->name = $this->currentRoom->name;
            $this->description = $this->currentRoom->description;
            $this->price_per_night = $this->currentRoom->price_per_night;
            $this->isRoomEditOpen = true;
        }
    }

    public function closeRoomEdit(): void
    {
        $this->isRoomEditOpen = false;
        $this->currentRoom = null;
        $this->clearFormAttributes();
        $this->resetErrorBag();
    }

    public function updateRoom(): void
    {
        $this->authorize('update', $this->currentRoom);
        $this->validate();
        $this->authorizeFormRequest();
        try {
            if ($this->currentRoom->exists()) {
                $this->currentRoom->update($this->getRoomData());
            }
            $this->closeRoomEdit();
            session()->flash('success', 'Room updated successfully!');
        } catch (\Exception $ex) {
            session()->flash('error', 'Something goes wrong!!');
        }
    }

    public function openRoomDelete(string $id): void
    {
        $this->isRoomDeleteOpen = true;
        $this->currentRoom = Room::find($id);
    }

    public function closeRoomDelete(): void
    {
        $this->isRoomDeleteOpen = false;
    }

    public function destroyRoom(): void
    {
        $this->authorize('delete', $this->currentRoom);
        try {
            if ($this->currentRoom->exists()) {
                $this->currentRoom->delete();
            }
            $this->closeRoomDelete();
            session()->flash('success', 'Room deleted successfully!');
        } catch (\Exception $ex) {
            session()->flash('error', 'Something goes wrong!!');
        }
    }

    public function sortBy($field): void
    {
        $this->sortDirection = $this->sortField === $field ?
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc' : 'asc';
        $this->sortField = $field;
    }

    public function render()
    {
        return view('livewire.rooms', [
            'rooms' => Room::orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
        ])->layout('layouts.app');
    }
}
