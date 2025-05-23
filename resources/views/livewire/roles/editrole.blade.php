<?php

use Livewire\Volt\Component;
use App\Models\Role;

new class extends Component {
    public $role;

    public function mount($roleId) {
        $this->role = Role::findOrFail($roleId);
    }
}; ?>

<div>
    {{ $role }}
</div>
