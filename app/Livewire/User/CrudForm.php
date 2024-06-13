<?php declare(strict_types=1);

namespace App\Livewire\User;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\PasswordValidationRules;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;
use Livewire\Component;
use Livewire\WithPagination;

class CrudForm extends Component
{
    use PasswordValidationRules;
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $isUserCreateOpen = false;
    public $isUserEditOpen = false;
    public $isUserDeleteOpen = false;
    public $currentUser = null;

    private array $queryString = ['sortField', 'sortDirection'];

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

    public function clearFormAttributes(): void
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 0;
    }

    private function getUserData(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'role' => $this->role,
        ];
    }

    public function openUserCreate(): void
    {
        $this->isUserCreateOpen = true;
    }

    public function closeUserCreate(): void
    {
        $this->isUserCreateOpen = false;
        $this->clearFormAttributes();
        $this->resetErrorBag();
    }

    public function storeUser(CreateNewUser $createNewUser): void
    {
        $validatedData = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:user'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'role' => [
                'required',
                'integer',
                'in:' . implode(',', Role::getValues())
            ],
        ]);

        $user = $createNewUser->create([...$validatedData, 'password_confirmation' => $this->password_confirmation]);
        $user->role = Role::from($this->role);
        $user->current_team_id = $user->teams()->first()->id;
        $user->save();
        $this->closeUserCreate();

    }

    public function messages(): array
    {
        return [
            'terms.accepted' => 'abc',
            'terms.accepted' => 'def',
        ];
    }

    public function openUserEdit(string $id): void
    {
        $this->currentUser = User::find($id);
        if ($this->currentUser) {
            $this->name = $this->currentUser->name;
            $this->email = $this->currentUser->email;
            $this->role = $this->currentUser->role->value;
            $this->isUserEditOpen = true;
        }
    }

    public function closeUserEdit(): void
    {
        $this->isUserEditOpen = false;
        $this->currentUser = null;
        $this->clearFormAttributes();
        $this->resetErrorBag();
    }

    public function updateUser(UpdateUserProfileInformation $updateUserProfileInformation): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('user', 'email')->ignore($this->currentUser->id)
            ],
            'role' => [
                'required',
                'integer',
                'in:' . implode(',', Role::getValues())
            ],
        ];

        if (!empty($this->password)) {
            $rules['password'] = $this->passwordRules();
        }

        $validatedData = $this->validate($rules);

        if ($this->currentUser->exists()) {
            $updateUserProfileInformation->update($this->currentUser, $validatedData);
            if (isset($rules['password'])) {
                $this->currentUser->forceFill([
                    'password' => Hash::make($this->password),
                ])->save();
            }
            $this->currentUser->role = Role::from($this->role);
            $this->currentUser->save();
        }
        $this->closeUserEdit();
    }

    public function openUserDelete(string $id): void
    {
        $this->isUserDeleteOpen = true;
        $this->currentUser = User::find($id);
    }

    public function closeUserDelete(): void
    {
        $this->isUserDeleteOpen = false;
    }

    public function destroyUser(): void
    {
        try {
            if ($this->currentUser->exists()) {
                $this->currentUser->delete();
            }
            $this->closeUserDelete();
            //session()->flash('success', 'User deleted successfully!');
        } catch (\Exception $ex) {
            //session()->flash('error', 'Something goes wrong!!');
        }
    }

    public function message(): array
    {
        return [
            'role.required' => 'The user role is required.',
            'role.integer' => 'The user role must be a valid number.',
            'role.in' => 'Invalid value for role.',
        ];
    }

    public function sortBy($field): void
    {
        $this->sortDirection = $this->sortField === $field ?
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc' : 'asc';
        $this->sortField = $field;
    }

    public function render()
    {
        return view('livewire.users', [
            'users' => User::orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
        ])->layout('layouts.app');
    }
}