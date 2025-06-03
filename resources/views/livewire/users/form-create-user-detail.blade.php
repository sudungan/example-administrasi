<?php

use Livewire\Volt\Component;
use App\Models\{User, AdditionRole};

new class extends Component {

    public $userId;
    public $first_name = "";
    public $last_name = "";
    public $place_of_birth = "";
    public $date_of_birth = "";
    public $address = "";
    public $phone_number = null;
    public $role_user = [];
    public $classroom_id = "";
    public $nis = "";
    public $nuptk = "";
    public $major_id = "";
    public $user = null;
    public $selectOptionRoles = null;

    public function mount($userId) {
        $this->userId = $userId;
        $this->user = User::with('role')->find($userId);
        $this->selectOptionRoles = AdditionRole::where('role_id', $this->user->role_id)->get();
    }

    public function storeDataUserGeneral() {

    }
}; ?>

<div>
    <div
        x-data="{
            first_name: $wire.entangle('first_name'),
            last_name: $wire.entangle('last_name'),
            address: $wire.entangle('address'),
            phone_number: $wire.entangle('phone_number'),
            place_of_birth: $wire.entangle('place_of_birth'),
            date_of_birth: $wire.entangle('date_of_birth'),
            nis: $wire.entangle('nis'),
            nuptk: $wire.entangle('nuptk'),
            major_id: $wire.entangle('major_id'),
            classroom_id: $wire.entangle('classroom_id'),
            role_user: $wire.entangle('role_user'),
        }"
        x-transition.duration.500ms class="flex bg-gray-400 fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-900">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white dark:semibold">
                        Form Data User Detail {{ $user->role->name }}
                    </h3>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <form wire:submit="storeDataUserGeneral" class="space-y-4">
                        <div class="grid gap-2 mb-4 grid-cols-2">
                            <!-- First Name -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Depan</label>
                                <input
                                    type="text"
                                    x-model="first_name"
                                    id="first_name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Type username"
                                >
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Belakang</label>
                                <input
                                    type="text"
                                    x-model="last_name"
                                    id="last_name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="example@example.com"
                                >
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                             <!-- Place of Birth -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="place_of_birth" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tempat Lahir</label>
                                <input
                                    type="text"
                                    x-model="place_of_birth"
                                    id="place_of_birth"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Type username"
                                >
                                @error('place_of_birth')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- date of Birth -->
                            <div class="relative col-span-2 sm:col-span-1">
                                <label for="date_of_birth" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Lahir</label>
                                {{-- <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    x-model="date_of_birth"
                                    id="datepicker-title"
                                    data-picker
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Select date"
                                > --}}

                                <div class="relative max-w-sm">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                    </svg>
                                </div>
                                <input id="datepicker-title" x-model="date_of_birth"  datepicker datepicker-title="Flowbite datepicker" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
                                </div>

                                @error('date_of_birth')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Password -->
                            <flux:input
                                wire:model="password"
                                :label="__('Password')"
                                type="password"
                                autocomplete="new-password"
                                :placeholder="__('Password')"
                                viewable
                            />

                            <div class="col-span-2 sm:col-span-1">
                                <label for="role_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan / Role</label>
                                    <select x-model="role_id" id="role_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option selected="">Select role</option>
                                            @forelse ($selectOptionRoles as $role)

                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @empty
                                                <option disabled> {{ __('addition role unexists') }}</option>
                                            @endforelse
                                    </select>
                                        @error('role_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                            </div>

                            <div class="flex gap-4">
                                <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Simpan
                                </button>
                                <button
                                    type="button"
                                    @click="closeFormMainRole"
                                    class="text-white inline-flex items-center bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@assets
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js" defer></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
@endassets

@script
    <script>
        new Pikaday({ field: $wire.$el.querySelector('[data-picker]') });

    </script>
@endscript
