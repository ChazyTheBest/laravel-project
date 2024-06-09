<?php declare(strict_types=1);

namespace App\Livewire;

use App\Models\Profile;
use App\Enums\Role;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfileComponent extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $isProfileCreateOpen = false;
    public $isProfileEditOpen = false;
    public $isProfileDeleteOpen = false;

    protected $queryString = ['sortField', 'sortDirection'];
    protected $currentProfile;
    protected $profiles;

    // Profile Fields
    public $name;
    public $phone;
    public $phone_2;
    public $address;
    public $city;
    public $state;
    public $postal_code;
    public $country;

    public function mount()
    {
        $user = Auth::user();

        $this->profiles = $user->hasRole(Role::STAFF)
                            ? Profile::query()
                            : $user->profiles()->getQuery();

        $this->profiles = $this->profiles
                            ->orderBy($this->sortField, $this->sortDirection)
                            ->paginate($this->perPage);

        $this->clearFormAttributes();
    }

    public function clearFormAttributes()
    {
        $this->name = '';
        $this->phone = '';
        $this->phone_2 = '';
        $this->address = '';
        $this->city = '';
        $this->state = '';
        $this->postal_code = '';
        $this->country = '';
    }

    public function openProfileCreate()
    {
        $this->isProfileCreateOpen = true;
    }

    public function closeProfileCreate()
    {
        $this->isProfileCreateOpen = false;
        $this->clearFormAttributes();
        $this->resetErrorBag();
    }

    public function storeProfile(StoreProfileRequest $request)
    {
        try {
            //Profile::create($request->validated());
            $this->closeProfileCreate();
            session()->flash('success', 'Profile created successfully!');
        } catch (\Exception $ex) {
            session()->flash('error', 'Something goes wrong!!');
        }
    }

    public function openProfileEdit(string $id)
    {
        $this->currentProfile = $this->profiles->find($id);
        $this->name = $this->currentProfile->name;
        $this->phone = $this->currentProfile->phone;
        $this->phone_2 = $this->currentProfile->phone_2;
        $this->address = $this->currentProfile->address;
        $this->city = $this->currentProfile->city;
        $this->state = $this->currentProfile->state;
        $this->postal_code = $this->currentProfile->postal_code;
        $this->country = $this->currentProfile->country;
        $this->isProfileEditOpen = true;
    }

    public function closeProfileEdit()
    {
        $this->currentProfile = null;
        $this->isProfileEditOpen = false;
        $this->clearFormAttributes();
        $this->resetErrorBag();
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            if ($this->currentProfile->exists()) {
                $this->currentProfile->update($request->validated());
            }
            $this->closeProfileEdit();
            session()->flash('success', 'Profile updated successfully!');
        } catch (\Exception $ex) {
            session()->flash('error', 'Something goes wrong!!');
        }
    }

    public function openProfileDelete(string $id)
    {
        $this->currentProfile = $this->profiles->find($id);
        $this->isProfileDeleteOpen = true;
    }

    public function closeProfileDelete()
    {
        $this->currentProfile = null;
        $this->isProfileDeleteOpen = false;
    }

    public function destroyProfile()
    {
        try {
            if ($this->currentProfile->exists()) {
                $this->currentProfile->delete();
            }
            $this->closeDeleteConfirm();
            session()->flash('success', 'Profile deleted successfully!');
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
        return view('livewire.profiles', [
            'profiles' => $this->profiles ?? new LengthAwarePaginator([], 0, $this->perPage)
        ])->layout('layouts.app');
    }
}
