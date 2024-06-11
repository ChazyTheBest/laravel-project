<?php declare(strict_types=1);

namespace App\Livewire\User;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class CrudForm extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $queryString = ['sortField', 'sortDirection'];

    //Form Field
    public $rId = null;
    public $isFormOpen = false;
    public $name, $email, $password;
    //Action
    public $dId = '';
    public $isDeleteModalOpen = false;

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field ?
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc'
            : 'asc';
        $this->sortField = $field;
    }

    // For Delete Feature Start
    public function deleteId($id)
    {
        $this->dId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function closeDelete()
    {
        $this->dId = '';
        $this->isDeleteModalOpen = false;
    }

    public function delete()
    {
        try {
            $user = User::find($this->dId);
            if ($user) {
                $user->delete();
            }
            $this->closeDelete();
            session()->flash('success', 'Record deleted successfully!!');
        } catch (\Exception $ex) {
            session()->flash('success', 'Something goes wrong!!');
        }
    }
    // For Delete Feature End

    // Create and Update Feature Start
    public function edit($id = null)
    {
        try {
            $this->rId = $id;
            if (!empty($this->rId)) {
                $user = User::find($this->rId);
                if ($user) {
                    $this->name = $user->name;
                    $this->email = $user->email;
                }
            }
            $this->isFormOpen = true;
        } catch (\Exception $ex) {
            session()->flash('success', 'Something goes wrong!!');
        }
    }

    public function save()
    {
        $ruleFields = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => ($this->rId) ? 'nullable' : 'required',
        ];
        $validatedData = $this->validate($ruleFields);
        $validatedData['user_id'] = auth()->id();

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }
        try {
            $userQuery = User::query();
            if (!empty($this->rId)) {
                $user = $userQuery->find($this->rId);
                if ($user) {
                    $user->update($validatedData);
                }
            } else {
                $userQuery->create($validatedData);
            }
            $this->closeFormModal();
        } catch (\Exception $ex) {
            session()->flash('success', 'Something goes wrong!!');
        }
    }

    public function closeFormModal()
    {
        $this->isFormOpen = false;
        $this->reset();
    }
    // Create and Update Feature End

    public function render()
    {
        return view('livewire.users', [
            'records' => User::orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ])->layout('layouts.app');
    }
}