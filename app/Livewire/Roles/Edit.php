<?php

namespace App\Livewire\Roles;
use App\Models\{Role, AdditionRole as Addition};
use Livewire\Attributes\On;

use Livewire\Component;

class Edit extends Component
{
    public $additionRole = null;
    public $edit = false;


    #[On('editAdditionRole')]
    public function load($idAdditionRole) {
        $this->additionRole = Addition::findOrFail($idAdditionRole);
        $this->edit = true;
    }
    public function render()
    {
        return view('livewire.roles.edit');
    }
}
