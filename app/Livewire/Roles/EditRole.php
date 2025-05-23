<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use App\Models\Role;

class EditRole extends Component
{
    public $roleId;
    public $role;
    public function mount($param = null) {
        $this->roleId = $param;
    }


    public function render()
    {
        return view('livewire.roles.edit-role');
    }
}
