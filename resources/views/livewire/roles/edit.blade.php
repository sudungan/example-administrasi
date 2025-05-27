<?php

use Livewire\Volt\Component;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

new class extends Component {
    public $edit = false;
    public $role;
    public $name = '';

    public function mount($idRole) {

        $this->role = Role::findOrFail($idRole);
    }

    public function updateDataRole($name) {
        
        $newName = $this->validate([
             'name' => ['required','min:5', 'string', 'unique:' . Role::class],
        ], [
            'name.required' => 'nama jabatan wajib diisi..',
            'name.min'      => 'Nama jabatan minimal 5 karakter..',
            'name.unique'   => 'Nama jabatan sudah dipakai..',
            'name.string'   => 'Nama Jabatan harus huruf'
        ]);

        // dd($validated);
        $this->role->update([
            'name'  => $name
        ]);

        $this->edit = false;

        $this->dispatch('edited-success');
    }

}; ?>

<div x-data="{
    edit: $wire.entangle('edit'),
    name: '{{ $role->name }}',
    oldName:  '{{ $role->name }}',
    closeModalEdit() {
        if(this.name !== this.oldName) {
            Swal.fire({
                title: 'Batalkan perubahan?',
                text: 'Data ini akan dikembalikan ke awal',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('close-edited');
                }
            });
        }else {
            $wire.dispatch('close-edited');
        }
    },
    sendDataUpdate() {
        $wire.updateDataRole(this.name);
    },
}"
{{-- x-init="$watch('name', value => console.log(name))" --}}
>
    <div class="flex bg-gray-400 fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
       <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm bg-gray-900">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Edit Role
                    </h3>
                    <button type="button" @click="closeModalEdit" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <form @submit.prevent="sendDataUpdate" class="space-y-4">
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
                            update
                        </button>
                        <button
                            @click="closeModalEdit"
                            type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-gray-200 rounded-lg border border-gray-200 hover:bg-gray-600 hover:text-blue-200 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
