<?php

use Livewire\Volt\Component;
use App\Models\User;

new class extends Component {
    public $user = null;

    public function mount($userId) {
        $this->user = User::with('role')->where('id', $userId)->first();

    }

}; ?>

<div>
    {{ $user }}
</div>
