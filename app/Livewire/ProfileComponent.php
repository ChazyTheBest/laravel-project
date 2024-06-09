<?php declare(strict_types=1);

namespace App\Livewire;

use App\Models\Profile;
use App\Enums\Role;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
    public $currentProfile = null;

    protected $queryString = ['sortField', 'sortDirection'];

    // Profile Fields
    public string $name;
    public string $phone;
    public string $phone_2;
    public string $address;
    public string $city;
    public string $state;
    public string $postal_code;
    public string $country;

    public function mount()
    {
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

    private function getProfileData()
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'phone_2' => $this->phone_2,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
        ];
    }

    protected function rules(): array
    {
        return (new StoreProfileRequest())->rules();
    }

    public function messages(): array
    {
        return (new StoreProfileRequest())->messages();
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

    public function storeProfile()
    {
        $this->validate();
        try {
            Auth::user()->profiles()->create($this->getProfileData());
            $this->closeProfileCreate();
            session()->flash('success', 'Profile created successfully!');
        } catch (\Exception $ex) {
            session()->flash('error', 'Something goes wrong!!');
        }
    }

    public function openProfileEdit(string $id)
    {
        $this->currentProfile = $this->fetchProfiles()->find($id);
        if ($this->currentProfile) {
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
    }

    public function closeProfileEdit()
    {
        $this->isProfileEditOpen = false;
        $this->currentProfile = null;
        $this->clearFormAttributes();
        $this->resetErrorBag();
    }

    public function updateProfile()
    {
        $this->validate();
        try {
            if ($this->currentProfile->exists()) {
                $this->currentProfile->update($this->getProfileData());
            }
            $this->closeProfileEdit();
            session()->flash('success', 'Profile updated successfully!');
        } catch (\Exception $ex) {
            session()->flash('error', 'Something goes wrong!!');
        }
    }

    public function openProfileDelete(string $id)
    {
        $this->isProfileDeleteOpen = true;
        $this->currentProfile = $this->fetchProfiles()->find($id);
    }

    public function closeProfileDelete()
    {
        $this->isProfileDeleteOpen = false;
    }

    public function destroyProfile()
    {
        try {
            if ($this->currentProfile->exists()) {
                $this->currentProfile->delete();
            }
            $this->closeProfileDelete();
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

    private function fetchProfiles()
    {
        $user = Auth::user();

        return $user->hasRole(Role::STAFF)
                        ? Profile::query()
                        : $user->profiles()->getQuery();
    }

    public function render()
    {
        $profiles = $this->fetchProfiles()
                            ->orderBy($this->sortField, $this->sortDirection)
                            ->paginate($this->perPage);

        return view('livewire.profiles', [
            'profiles' => $profiles ?? new LengthAwarePaginator([], 0, $this->perPage)
        ])->layout('layouts.app');
    }
}
