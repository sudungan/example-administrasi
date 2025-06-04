{{-- <div
    x-data="{
        isShowFormGeneral: $wire.entangle('createMainRole'),
        name: $wire.entangle('name'),
        additionDetailProfile: $wire.entangle('additionDetailProfile'),
        email: $wire.entangle('email'),
        password: $wire.entangle('password'),
        role_id: $wire.entangle('role_id'),
        showFormRoleGeneral() {
        this.isShowFormGeneral = true;
    },
    resetFormUserGeneral() {
        this.isShowFormGeneral = false;
        this.name = '';
        this.role_id = '';
        this.email = '';
        this.password = '';
    },
    closeFormMainRole() {
        if(
            (this.name && this.name.trim() !== '') ||
            (this.password && this.password.trim() !== '') ||
            (this.email && this.email.trim() !== '') ||
            (this.role_id && this.role_id.trim() !== '')
           ) {
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
                        this.resetFormUserGeneral();
                        this.isShowFormGeneral = false;
                    }
            });
        }else {
            this.isShowFormGeneral = false;
            $wire.resetError();
        }
    },
    closeItem() {

        this.isShowFormGeneral = false;
    },
    deleteConfirm(idUser) {
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
                    $wire.deleteUser(idUser);
                    Swal.fire(
                        'Terhapus!',
                        'Data berhasil dihapus.',
                        'success'
                    );
                }
        });
    },
    showDetailUser(idUser) {
        this.isShowFormGeneral = false;
        console.log(this.isShowFormGeneral);

    }
}"
x-init="
    window.addEventListener('data-user-general', ()=> {
        this.isShowFormGeneral = false;
    });

     window.addEventListener('close', ()=> {
       closeItem();
    });

    $(document).ready(function() {
    $('#select-role').select2({
        placeholder: 'pilih jabatan'
    });
});
"
> --}}
<div id="app">
    <section v-show="!isShowFormGeneral"  class="w-full">
        @include('partials.users-heading')
    </section>

    <div class="flex gap-4">
        <button
            v-show="!isShowFormGeneral"
            @click="showFormRoleGeneral"
            type="button"
            class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
            user
        </button>
    </div>

     <div
        v-show="isShowFormGeneral"
        class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-3xl max-h-full">
            <div class="relative bg-gray-300 rounded-lg shadow-sm dark:bg-gray-900">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white dark:semibold">
                        Form Data Umum
                    </h3>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    <form @submit.prevent="storeDataUserGeneral" class="space-y-4">
                        <div class="grid gap-2 mb-4 grid-cols-2">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama User</label>
                                <input
                                    type="text"
                                    v-model="dataUserGeneral.name"
                                    id="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Type username"
                                >
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email Address</label>
                                <input
                                    type="email"
                                    v-model="dataUserGeneral.email"
                                    id="email"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="example@example.com"
                                >
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <flux:input
                                v-model="dataUserGeneral.password"
                                :label="__('Password')"
                                type="password"
                                autocomplete="new-password"
                                :placeholder="__('Password')"
                                viewable
                            />

                            <div class="col-span-2 sm:col-span-1">
                                <label for="role_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan Tambahan/ addition Role</label>
                                    <select v-model="dataUserGeneral.role_id" id="role_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option selected="">Select role</option>
                                            {{-- @forelse ($mainRole as $key => $value)
                                                @if ($key != 'admin')
                                                    <option value="{{ $value }}">{{ $key }}</option>
                                                @endif
                                            @endforeach --}}
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

      <div
        v-show="!isShowFormGeneral"
        class="relative shadow-md sm:rounded-lg">
        <div class="justify-items-end pb-4 bg-white dark:bg-zinc-800">
            <label for="table-search" class="sr-only">Search</label>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" id="table-search" wire:model.live="search" class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for items">
            </div>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class=" bg-gray-200 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        #
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nama
                    </th>
                        <th scope="col" class="px-6 py-3">
                        Alamat Email
                    </th>
                        <th scope="col" class="px-6 py-3">
                        Jabatan Utama / Role
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>

                <tr  class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{-- {{ $loop->iteration }} --}}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{-- {{ $user->name }} --}}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{-- {{ $user->email }} --}}
                    </th>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{-- @php
                            $role = $user->role->name;
                            $colors = [
                                'admin' => 'blue',
                                'guru' => 'green',
                                'siswa' => 'indigo',
                                'orang-tua' => 'purple'
                            ];
                        @endphp

                        @if (isset($colors[$role]))
                            <span class="bg-{{ $colors[$role] }}-100 text-{{ $colors[$role] }}-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-{{ $colors[$role] }}-700 dark:text-{{ $colors[$role] }}-400 border border-{{ $colors[$role] }}-400">
                                {{ $role }}
                            </span>
                        @endif --}}
                    </th>
                        <td class="px-6 py-4 flex gap-3">
                            {{-- <a @click="showDetailUser( {{$user->id}} )"
                                data-tooltip-target="tooltip-show-{{$user->id}}" class="cursor-pointer font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                    <span id="tooltip-show-{{$user->id}}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                    show
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </span>
                            </a>
                            <a x-on:click="$wire.editRole({{$user->id}})" data-tooltip-target="tooltip-edit-{{$user->id}}" class="cursor-pointer font-medium text-gray-600 dark:text-gray-500 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                <span id="tooltip-edit-{{$user->id}}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                    edit
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </span>
                            </a>
                            <a @click="deleteConfirm( {{$user->id}} )" data-tooltip-target="tooltip-delete-{{$user->id}}" class="cursor-pointer font-medium text-red-600 dark:text-red-500 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                <span id="tooltip-delete-{{$user->id}}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                    delete
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </span>
                            </a> --}}
                        </td>
                    </tr>
                    <th scope="row" class="px-6  text-yellow-800 rounded-lg bg-yellow-50 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <span class="font-medium"> Tidak ada data user..</span>
                    </th>
                </tr>
                {{-- @forelse ($listUser as $user)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->iteration }}
                        </th>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $user->name }}
                        </th>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $user->email }}
                        </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            @php
                                $role = $user->role->name;
                                $colors = [
                                    'admin' => 'blue',
                                    'guru' => 'green',
                                    'siswa' => 'indigo',
                                    'orang-tua' => 'purple'
                                ];
                            @endphp

                            @if (isset($colors[$role]))
                                <span class="bg-{{ $colors[$role] }}-100 text-{{ $colors[$role] }}-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-{{ $colors[$role] }}-700 dark:text-{{ $colors[$role] }}-400 border border-{{ $colors[$role] }}-400">
                                    {{ $role }}
                                </span>
                            @endif
                        </th>
                        <td class="px-6 py-4 flex gap-3">
                            <a @click="showDetailUser( {{$user->id}} )"
                                data-tooltip-target="tooltip-show-{{$user->id}}" class="cursor-pointer font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                    <span id="tooltip-show-{{$user->id}}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                    show
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </span>
                            </a>
                            <a x-on:click="$wire.editRole({{$user->id}})" data-tooltip-target="tooltip-edit-{{$user->id}}" class="cursor-pointer font-medium text-gray-600 dark:text-gray-500 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                <span id="tooltip-edit-{{$user->id}}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                    edit
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </span>
                            </a>
                            <a @click="deleteConfirm( {{$user->id}} )" data-tooltip-target="tooltip-delete-{{$user->id}}" class="cursor-pointer font-medium text-red-600 dark:text-red-500 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                <span id="tooltip-delete-{{$user->id}}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                    delete
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <th scope="row" class="px-6  text-yellow-800 rounded-lg bg-yellow-50 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <span class="font-medium"> Tidak ada data user..</span>
                    </th>
                @endforelse --}}
            </tbody>
        </table>
    </div>

</div>


    {{-- <div class="flex gap-4">
        <button
            x-show="!isShowFormGeneral"
            x-on:click="showFormRoleGeneral"
            type="button"
            class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
            user
        </button>
    </div>

    <div
        x-show="isShowFormGeneral"
        x-cloak
        x-transition.duration.200ms
        class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-3xl max-h-full">
            <div class="relative bg-gray-300 rounded-lg shadow-sm dark:bg-gray-900">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white dark:semibold">
                        Form Data Umum
                    </h3>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    <form wire:submit="storeDataUserGeneral" class="space-y-4">
                        <div class="grid gap-2 mb-4 grid-cols-2">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama User</label>
                                <input
                                    type="text"
                                    x-model="name"
                                    id="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Type username"
                                >
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email Address</label>
                                <input
                                    type="email"
                                    x-model="email"
                                    id="email"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="example@example.com"
                                >
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <flux:input
                                wire:model="password"
                                :label="__('Password')"
                                type="password"
                                autocomplete="new-password"
                                :placeholder="__('Password')"
                                viewable
                            />

                            <div class="col-span-2 sm:col-span-1">
                                <label for="role_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan Tambahan/ addition Role</label>
                                    <select x-model="role_id" id="role_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option selected="">Select role</option>
                                            @forelse ($mainRole as $key => $value)
                                                @if ($key != 'admin')
                                                    <option value="{{ $value }}">{{ $key }}</option>
                                                @endif
                                            @endforeach
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

    <div
        x-show="!isShowFormGeneral"
        class="relative shadow-md sm:rounded-lg">
        <div class="justify-items-end pb-4 bg-white dark:bg-zinc-800">
            <label for="table-search" class="sr-only">Search</label>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" id="table-search" wire:model.live="search" class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for items">
            </div>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class=" bg-gray-200 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        #
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nama
                    </th>
                        <th scope="col" class="px-6 py-3">
                        Alamat Email
                    </th>
                        <th scope="col" class="px-6 py-3">
                        Jabatan Utama / Role
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($listUser as $user)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->iteration }}
                        </th>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $user->name }}
                        </th>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $user->email }}
                        </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            @php
                                $role = $user->role->name;
                                $colors = [
                                    'admin' => 'blue',
                                    'guru' => 'green',
                                    'siswa' => 'indigo',
                                    'orang-tua' => 'purple'
                                ];
                            @endphp

                            @if (isset($colors[$role]))
                                <span class="bg-{{ $colors[$role] }}-100 text-{{ $colors[$role] }}-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-{{ $colors[$role] }}-700 dark:text-{{ $colors[$role] }}-400 border border-{{ $colors[$role] }}-400">
                                    {{ $role }}
                                </span>
                            @endif
                        </th>
                        <td class="px-6 py-4 flex gap-3">
                            <a @click="showDetailUser( {{$user->id}} )"
                                data-tooltip-target="tooltip-show-{{$user->id}}" class="cursor-pointer font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                    <span id="tooltip-show-{{$user->id}}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                    show
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </span>
                            </a>
                            <a x-on:click="$wire.editRole({{$user->id}})" data-tooltip-target="tooltip-edit-{{$user->id}}" class="cursor-pointer font-medium text-gray-600 dark:text-gray-500 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                <span id="tooltip-edit-{{$user->id}}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                    edit
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </span>
                            </a>
                            <a @click="deleteConfirm( {{$user->id}} )" data-tooltip-target="tooltip-delete-{{$user->id}}" class="cursor-pointer font-medium text-red-600 dark:text-red-500 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                <span id="tooltip-delete-{{$user->id}}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                    delete
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <th scope="row" class="px-6  text-yellow-800 rounded-lg bg-yellow-50 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <span class="font-medium"> Tidak ada data user..</span>
                    </th>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($additionDetailProfile)
        <div
            class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-3xl max-h-full">
                <div class="relative bg-gray-300 rounded-lg shadow-sm dark:bg-gray-900">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white dark:semibold">
                            Form Data Detail Profile
                        </h3>
                    </div>
                    <div class="p-4 md:p-5 space-y-4">
                        <form wire:submit="storeDetailProfile" class="space-y-4">
                            <div class="grid gap-2 mb-4 grid-cols-2">
                                <div class="col-span-2 sm:col-span-1">
                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama User</label>
                                    <input
                                        type="text"
                                        x-model="name"
                                        id="name"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Type username"
                                    >
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email Address</label>
                                    <input
                                        type="email"
                                        x-model="email"
                                        id="email"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="example@example.com"
                                    >
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <flux:input
                                    wire:model="password"
                                    :label="__('Password')"
                                    type="password"
                                    autocomplete="new-password"
                                    :placeholder="__('Password')"
                                    viewable
                                />

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="role_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan Tambahan/ addition Role</label>
                                        <select x-model="role_id" id="role_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option selected="">Select role</option>
                                                @forelse ($mainRole as $key => $value)
                                                    @if ($key != 'admin')
                                                        <option value="{{ $value }}">{{ $key }}</option>
                                                    @endif
                                                @endforeach
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
    @endif

    @if ($userId)
        <livewire:users._card-user-detail :userId="$userId" />
    @endif --}}
{{-- </div> --}}
{{-- @script --}}
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
{{-- @endscript --}}


<script type="module">
    import { createApp, ref, reactive, onMounted   } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js'
    import axios from 'https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/esm/axios.min.js';

  createApp({
    setup() {
        const message = ref('Hello Vue!')
        const isShowFormGeneral = ref(false)
        const dataUserGeneral = reactive({ name: '',  email: '',  password: '',  role_id: '' })
        const listUser = ref([]);
        const showFormRoleGeneral = ()=> { isShowFormGeneral.value = true }

        const closeFormMainRole = () => {  isShowFormGeneral.value = false }

        onMounted(async ()=> {
             getAllDataUser()
        });
        function storeDataUserGeneral() {
            console.log(dataUserGeneral.password)
        }

        async function getAllDataUser() {
            const result = await axios.get('getListUser')
           listUser.value = result.data.data
        }

      return {
        message, isShowFormGeneral, showFormRoleGeneral, dataUserGeneral, storeDataUserGeneral, closeFormMainRole,
        listUser,
      }
    }
  }).mount('#app')
</script>
