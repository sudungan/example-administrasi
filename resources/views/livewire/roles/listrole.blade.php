<?php

use Livewire\Volt\Component;
use App\Models\Role;

new class extends Component {
    public $role = [];
    public $name = "";
    public $isOpenModal = false;

    public function storeDataRole() {
        $this->validate([
            'name' => ['required','min:5', 'string', 'lowercase', 'unique:' . Role::class],
        ], [
            'name.required' => 'nama jabatan wajib diisi..',
            'name.min'      => 'Nama jabatan minimal 5 karakter..',
            'name.unique'   => 'Nama jabatan sudah dipakai..    '
        ]);

        Role::create([
            'name'  => $this->name
        ]);

        $this->reset('name');

    }

    public function cancelSend() {
        $this->reset('name');
        $this->resetErrorBag();
    }

    public function openModal() {
        return $this->isOpenModal = true;
    }
}; ?>


<div>
    <section class="w-full">
        @include('partials.roles-heading')
    </section>


<div x-data="{
        isShowFormCreate: false,
        name: $wire.entangle('name'),
        openModalCreate() {
            this.isShowFormCreate = true;
        },
        closeCreate() {
            if(this.name != '') {
            let confirmClose = window.confirm('Yakin untuk membatalkan?')
                if (confirmClose) {
                    $wire.cancelSend();
                    this.isShowFormCreate = false;
                    this.name = '';
                }
            }else {
                $wire.cancelSend();
                this.isShowFormCreate = false;
            }
        },
        saveRole() {
            $wire.storeDataRole()
        }
     }">
    <button
        x-show="!isShowFormCreate"
        type="button"
        @click="openModalCreate"
        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
        role
    </button>
    <div x-show="isShowFormCreate" class="flex bg-gray-400 fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm bg-gray-900">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                       Create New Role
                    </h3>
                    <button type="button" x-on:click="closeCreate" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                   <form @submit.prevent="saveRole" class="space-y-4">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Jabatan</label>
                        <input type="text"  x-model="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="write role name here.." />
                         @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                         @enderror
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-4 md:p-5 border-gray-200 rounded-b dark:border-gray-600">
                        <button
                            type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Save
                        </button>
                        <button
                            @click="closeCreate"
                            type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-gray-200 rounded-lg border border-gray-200 hover:bg-gray-600 hover:text-blue-200 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

