<?php

use Livewire\Volt\Component;
use App\Models\Role;

new class extends Component {
    public $role = [];
}; ?>

<div>
    <section class="w-full">
        @include('partials.roles-heading')
</section>  
</div>
