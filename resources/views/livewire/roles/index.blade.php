<div
x-data="{
    name: $wire.entangle('name'),
    currentView: 'table',
    role_id: $wire.entangle('role_id'),
    edit: $wire.entangle('edit'),
    closeFormCreate() {
        if(this.name != '' || this.role_id){
             Swal.fire({
                    title: 'yakin membatalkan?',
                    text: 'Data ini akan dihapus dan tidak bisa dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.currentView = 'table'
                        this.name = ''
                        this.role = ''
                    }
            });
        } else {
        this.currentView = 'table'
        $wire.resetError();
        }
    },
    createdSuccessNotification() {
         let createdNotifiation = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {  toast.onmouseenter = Swal.stopTimer; toast.onmouseleave = Swal.resumeTimer;  }
        });

        createdNotifiation.fire({
            icon: 'success',
            title: 'berhasil disimpan.'
        });
    },
    showTable() {
        this.currentView = 'table'
    },

    showFormCreate() {
        $wire.resetError();
        this.currentView = 'create'
    },
    showEditForm() {
        this.currentView = 'edit'
    },
    sendDataRole() {
        $wire.storeDataRole();
    },
    deleteConfirmAdditionRole(additionRoleId) {
         Swal.fire({
                title: 'Apakah kamu yakin?',
                text: 'Data ini akan dihapus dan tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deleteAdditionRole(additionRoleId);
                    Swal.fire(
                        'Terhapus!',
                        'Data berhasil dihapus.',
                        'success'
                    );
                }
        });
    }
}"
{{-- terima event kiriman dari livewire reactive melalui window--}}
@success-created-notification.window="currentView = 'table'"
x-init="
    window.addEventListener('success-update-notification', () => {
        let updateNotification = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        updateNotification.fire({
            icon: 'success',
            title: 'berhasil diupdate'
        });
     });

    window.addEventListener('success-created-notification', (event) => {
        setTimeout(()=> {
            createdSuccessNotification()
        },200);
    });
"
>
    <section class="w-full">
        @include('partials.roles-heading')
    </section>

        <div class="flex gap-4">
            <flux:button
                x-show="currentView =='table'"
                @click="showFormCreate" size="sm"
                >
                 <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                Role
            </flux:button>

        </div>
            <template x-teleport="body">
                <div
                    x-show="currentView == 'create'"
                    x-cloak
                    x-transition.duration.300ms
                    class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    @include('livewire.roles._card-form-create')
                </div>
            </template>
        <div
            x-show="currentView =='table'"
            x-cloak
            x-transition.duration.100ms
            class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="justify-items-end pb-4 bg-white dark:bg-zinc-800">
                <label for="table-search" class="sr-only">Search</label>
                <div class="relative mt-1">
                    <input type="text" id="table-search" wire:model.live="search" class="block pt-2 ps-10 mt-2 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for items">
                </div>
            </div>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class=" bg-gray-200 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">
                            #
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jabatan Utama
                        </th>
                         <th scope="col" class="px-6 py-3">
                            Jabatan Tambahan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($listRole as $role)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $loop->iteration }}
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $role->name }}
                            </th>
                             <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                @forelse ($role->additionRole as $item)
                                <span class="flex gap-2 bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                                    {{ $item->name }}
                                    <div class="gap-2 flex">
                                        <button type="button" wire:click="editRole( {{ $item->id }} )" class="text-gray-600 dark:text-gray-500 cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </button>
                                        <button type="button" @click="deleteConfirmAdditionRole( {{ $item->id }} )" class="text-red-600 dark:text-red-500 cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </span>
                                @empty
                                    <span class=" text-yellow-800 rounded-lg py-4 font-medium text-gray-900"> jabatan tambahan belum tersedia..</span>
                                @endforelse
                            </th>
                        </tr>
                    @empty
                        <th scope="row" class="px-6  text-yellow-800 rounded-lg bg-yellow-50 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <span class="font-medium"> Tidak ada data jabatan..</span>
                        </th>
                    @endforelse
                </tbody>
            </table>
            {{ $listRole->links() }}
        </div>

        <div x-show="currentView =='edit'">
            {{ __('EDIT') }}
        </div>
        {{-- <livewire:roles.edit :idRole="$selectedRoleId" /> --}}
</div>


