<?php

namespace App\Livewire\Roles;

use Livewire\Attributes\{On, Url};
use Livewire\Component;
use App\Models\{Role, AdditionRole};
use App\Helpers\MainRole;
use Illuminate\Database\Eloquent\Collection;

use Livewire\{WithPagination};

class Index extends Component
{
    use WithPagination;

    public $role = [];
    public $role_id = null;
    public $name = "";
    public $edit = false;
    public $listRole = [];
    public $createSuccess = false;
    public $currentView = "table";
    public ?AdditionRole $editingAdditionRole = null;

    #[Url]
    public ?string $search = '';

    public function mount() {
        $this->checkRole();
        $this->getRole();
    }

    public function getRole() {
        $this->listRole = Role::with('additionRole')->get();
    }

    public function checkRole() {

        $existRole = Role::pluck('name')->toArray();
        $listRole = ['admin'];

        $newRoles = array_diff($listRole, $existRole);
         if (!empty($newRoles)) {
            foreach ($newRoles as $key => $role) {
               Role::create(['name'=> $role]);
            }

        }
    }

    public function closeCreateForm() {
        $this->reset('name');
        $this->reset('role_id');
        $this->dispatch('success-created-notification', currentView: 'table');
    }

    public function editAddition(AdditionRole $additionRole) {
        $this->editingAdditionRole = $additionRole;
        $this->getRole();
    }

    #[On('close-edited')]
    public function closeEdit() {
        $this->edit = false;
    }

    #[On('edited-success')]
    public function closeUpdate() {
        $this->edit = false;
        $this->dispatch('success-update-notification');
    }

    public function resetError() {
        $this->resetErrorBag(['name', 'role_id']);
    }

    public function updatetingCreate() {
        $this->resetPage();
    }

    public function updatingSearch() {
        $this->resetPage();
    }

    #[On('role-updated')]
    public function render()
    {
          $query = Role::with('additionRole');

        if (!empty(trim($this->search))) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
            $query = $query->latest();

        return view('livewire.roles.index', [
                'mainRole'  => MainRole::role,
                'listRole' => $query->paginate(5)
        ]);
    }

    public function cancelSend() {
        $this->reset('name');
        $this->resetErrorBag();
    }

       public function storeDataRole() {
        $this->validate([
            'name' => ['required','min:4', 'string', 'unique:' . AdditionRole::class],
            'role_id'   => 'required',
        ], [
            'name.required'     => 'nama jabatan tambahan wajib diisi..',
            'name.min'          => 'Nama jabatan tambahan minimal 5 karakter..',
            'name.unique'       => 'Nama jabatan tambahan sudah dipakai..',
            'name.string'       => 'Nama Jabatan tambahan harus huruf',
            'role_id.required'  => 'nama jabatan utama wajib dipilih..',
        ]);

        AdditionRole::create([
            'name'  => $this->name,
            'role_id'   => $this->role_id,
        ]);
        $this->resetError();
        $this->closeCreateForm();
    }

    public function deleteAdditionRole($additionRoleId) {
        AdditionRole::findOrFail($additionRoleId)->delete();
    }
}
