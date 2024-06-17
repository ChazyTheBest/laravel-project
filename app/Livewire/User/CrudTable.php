<?php declare(strict_types=1);

namespace App\Livewire\User;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\PasswordValidationRules;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Enums\Role;
use App\Models\User;
use App\Traits\WithModelState;
use App\Traits\WithTable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;
use Livewire\Component;
use Livewire\WithPagination;

class CrudTable extends Component
{
    use AuthorizesRequests;
    use PasswordValidationRules;
    use WithModelState;
    use WithPagination;
    use WithTable;

    public ?User $currentUser = null;

    // User Fields
    public string $name;
    public string $email;
    public string $password;
    public string $password_confirmation;
    public int $role;
    public string $terms = 'on';

    public function mount(): void
    {
        $this->clearFormAttributes();
    }

    public function render()
    {
        return view('livewire.user.crud-table', [
            'users' => User::orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
        ]);
    }

    public function openCreateModal(): void
    {
        $this->clearFormAttributes();
        $this->openModal(
            'create',
            __('Create User'),
            'components.user.form-fields',
            'storeUser',
            'closeModal',
            'Create'
        );
    }

    /**
     * @throws AuthorizationException
     */
    public function storeUser(CreateNewUser $createNewUser): void
    {
        $this->authorize('create', User::class);
        $user = $createNewUser->create([...$this->validateForm(), 'password_confirmation' => $this->password_confirmation]);
        $user->role = Role::from($this->role);
        $user->current_team_id = $user->teams()->first()->id;
        $user->save();
        $this->closeModal();

    }

    public function openEditModal(string $id): void
    {
        $this->clearFormAttributes();
        $this->currentUser = User::find($id);
        if ($this->currentUser) {
            $this->name = $this->currentUser->name;
            $this->email = $this->currentUser->email;
            $this->role = $this->currentUser->role->value;
        }

        $this->openModal(
            'edit',
            __('Edit User'),
            'components.user.form-fields',
            'updateUser',
            'closeModal',
            'Edit'
        );
    }

    /**
     * @throws AuthorizationException
     */
    public function updateUser(UpdateUserProfileInformation $updateUserProfileInformation): void
    {
        $this->authorize('update', $this->currentUser);

        if ($this->currentUser->exists()) {
            $updateUserProfileInformation->update($this->currentUser, $this->validateForm());
            $this->currentUser->role = Role::from($this->role);
            $this->currentUser->save();

            if (isset($this->password)) {
                $this->currentUser->forceFill([
                    'password' => Hash::make($this->password),
                ])->save();
            }
        }

        $this->closeModal();
    }

    public function openDeleteModal(string $id): void
    {
        $this->currentUser = User::find($id);
        $this->openModal(
            'delete',
            __('Delete User'),
            __('Are you sure you want to delete this user?'),
            'destroyUser',
            'closeModal',
            'Delete'
        );
    }

    /**
     * @throws AuthorizationException
     */
    public function destroyUser(): void
    {
        $this->authorize('delete', $this->currentUser);

        if ($this->currentUser->exists()) {
            $this->currentUser->delete();
        }

        $this->closeModal();
    }

    protected function message(): array
    {
        return [
            'role.required' => 'The user role is required.',
            'role.integer' => 'The user role must be a valid number.',
            'role.in' => 'Invalid value for role.',
        ];
    }

    private function clearFormAttributes(): void
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 0;
    }

    private function validateForm(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                $this->currentUser
                    ? Rule::unique('user', 'email')->ignore($this->currentUser->id)
                    : Rule::unique('user', 'email'),
            ],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'role' => [
                'required',
                'integer',
                Rule::in(Role::cases(), 'value'),
            ],
        ];

        if (!empty($this->password)) {
            $rules['password'] = $this->passwordRules();
        }

        return $this->validate($rules);
    }
}
