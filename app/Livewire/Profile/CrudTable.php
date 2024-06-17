<?php declare(strict_types=1);

namespace App\Livewire\Profile;

use App\Enums\Role;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use App\Traits\WithModelState;
use App\Traits\WithTable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
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

    public ?Profile $currentModel = null;

    // Profile Fields
    public string $name;
    public string $phone;
    public string $phone_2;
    public string $address;
    public string $city;
    public string $state;
    public string $postal_code;
    public string $country;

    public function mount(): void
    {
        $this->clearFormAttributes();
    }

    public function render()
    {
        $profiles = $this->fetchProfiles()
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.profile.crud-table', [
            'profiles' => $profiles ?? new LengthAwarePaginator([], 0, $this->perPage)
        ]);
    }

    public function openCreateModal(): void
    {
        $this->clearFormAttributes();
        $this->openModal(
            'create',
            __('Create Profile'),
            'components.profile.form-fields',
            'storeProfile',
            'closeModal',
            'Create'
        );
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function storeProfile(): void
    {
        $this->authorize('create', Profile::class);
        $this->validateAndAuthorizeProfileRequest();
        Auth::user()->profiles()->create($this->getProfileData());
        $this->closeModal();
    }

    /**
     * @throws AuthorizationException
     */
    public function openEditModal(string $id): void
    {
        $this->clearFormAttributes();
        $this->currentModel = Profile::find($id);
        // check this until users get their own profile show page
        $this->authorize('view', $this->currentModel);
        if ($this->currentModel) {
            $this->name = $this->currentModel->name;
            $this->phone = $this->currentModel->phone;
            $this->phone_2 = $this->currentModel->phone_2;
            $this->address = $this->currentModel->address;
            $this->city = $this->currentModel->city;
            $this->state = $this->currentModel->state;
            $this->postal_code = $this->currentModel->postal_code;
            $this->country = $this->currentModel->country;
        }

        $this->openModal(
            'create',
            __('Edit Profile'),
            'components.profile.form-fields',
            'updateProfile',
            'closeModal',
            'Edit'
        );
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function updateProfile(): void
    {
        $this->authorize('update', $this->currentModel);
        $this->validateAndAuthorizeProfileRequest();

        if ($this->currentModel->exists()) {
            $this->currentModel->update($this->getProfileData());
        }

        $this->closeModal();
    }

    /**
     * @throws AuthorizationException
     */
    public function openDeleteModal(string $id): void
    {
        $this->currentModel = Profile::find($id);
        // check this until users get their own profile show page
        $this->authorize('delete', $this->currentModel);

        $this->openModal(
            'delete',
            __('Delete Profile'),
            __('Are you sure you want to delete this profile?'),
            'destroyProfile',
            'closeModal',
            'Delete'
        );
    }

    /**
     * @throws AuthorizationException
     */
    public function destroyProfile(): void
    {
        $this->authorize('delete', $this->currentModel);
        if ($this->currentModel->exists()) {
            $this->currentModel->delete();
        }
        $this->closeModal();
    }

    private function clearFormAttributes(): void
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

    private function fetchProfiles(): Builder
    {
        $user = Auth::user();

        return $user->hasRole(Role::STAFF)
            ? Profile::query()
            : $user->profiles()->getQuery();
    }

    private function getProfileData(): array
    {
        return [
            'id' => $this->currentModel->id ?? null,
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

    /**
     * @throws ValidationException
     * @throws AuthorizationException
     */
    private function validateAndAuthorizeProfileRequest(): void
    {
        $request = $this->currentModel
            ? new UpdateProfileRequest()
            : new StoreProfileRequest();

        $data = $this->getProfileData();

        $request->merge($data);

        Validator::make($data, $request->rules(), $request->messages())->validate();

        if (!$request->authorize()) {
            $request->failedAuthorization();
        }
    }
}
