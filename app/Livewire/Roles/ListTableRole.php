<?php

namespace App\Livewire\Roles;
use App\Models\Role;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class ListTableRole extends Component
{
    use WithPagination;
    public Collection $roles;
    public Role $role;

    #[On('role-created')]
    #[On('role-deleted')]
    public function render()
    {
        return view('livewire.roles.list-table-role',[
            'listRole'  => $this->getRole()
        ]);
    }

    public function getRole() {
        return Role::paginate(5);
    }

    public function showEditFormRole($roleId) {
        $this->dispatch('open-edit-form-role', roleId: $roleId);
    }

    public function deleteRole($idRole) {
        $role = Role::find($idRole)->delete();
        $this->dispatch('role-deleted');
    }
}
