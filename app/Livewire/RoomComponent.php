<?php declare(strict_types=1);

namespace App\Livewire;

use App\Models\Room;
use App\Enums\Role;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Livewire\Component;
use Livewire\WithPagination;

class RoomComponent extends Component
{
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
    public float $price_per_night;

    public function mount()
    {
        $this->clearFormAttributes();
    }

    public function clearFormAttributes()
    {
        $this->number = 0;
        $this->capacity = 0;
        $this->beds = 0;
        $this->name = '';
        $this->description = '';
        $this->price_per_night = 0;
    }

    private function getRoomData()
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
        return (new StoreRoomRequest())->rules();
    }

    public function messages(): array
    {
        return (new StoreRoomRequest())->messages();
    }

    public function openRoomCreate()
    {
        $this->isRoomCreateOpen = true;
    }

    public function closeRoomCreate()
    {
        $this->isRoomCreateOpen = false;
        $this->clearFormAttributes();
        $this->resetErrorBag();
    }

    public function storeRoom()
    {
        $this->validate();
        try {
            Room::create($this->getRoomData());
            $this->closeRoomCreate();
            session()->flash('success', 'Room created successfully!');
        } catch (\Exception $ex) {
            die($ex);
            session()->flash('error', 'Something goes wrong!!');
        }
    }

    public function openRoomEdit(string $id)
    {
        $this->currentRoom = Room::find($id);
        if ($this->currentRoom) {
            $this->name = $this->currentRoom->name;
            $this->phone = $this->currentRoom->phone;
            $this->phone_2 = $this->currentRoom->phone_2;
            $this->address = $this->currentRoom->address;
            $this->city = $this->currentRoom->city;
            $this->state = $this->currentRoom->state;
            $this->postal_code = $this->currentRoom->postal_code;
            $this->country = $this->currentRoom->country;
            $this->isRoomEditOpen = true;
        }
    }

    public function closeRoomEdit()
    {
        $this->isRoomEditOpen = false;
        $this->currentRoom = null;
        $this->clearFormAttributes();
        $this->resetErrorBag();
    }

    public function updateRoom()
    {
        $this->validate();
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

    public function openRoomDelete(string $id)
    {
        $this->isRoomDeleteOpen = true;
        $this->currentRoom = Room::find($id);
    }

    public function closeRoomDelete()
    {
        $this->isRoomDeleteOpen = false;
    }

    public function destroyRoom()
    {
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

    public function sortBy($field)
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
