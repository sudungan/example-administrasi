<x-layouts.app :title="__('Pengguna')" appName="Example Administrasi">
    <div x-data="dataRole()" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl" wire:ignore>
        @include('partials.settings-heading')


         <div class="relative h-full flex-1 overflow-hidden rounded-xl">
            <div class="flex gap-4">
                {{-- tombol-add user --}}
                <button
                    x-show="currentView === 'table'"
                    @click="showFormCreate"
                    type="button"
                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    role
                </button>
            </div>

            {{-- form create data user general--}}
            {{-- <div
                v-cloak
                v-show="currentView === 'create'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    @include('users._card-form-create')
            </div> --}}

            {{-- form create data user detail--}}
            {{-- <div
                v-cloak
                v-show="currentView === 'create-user-detail'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <form-user-detail :role-id="roleId" :user-id="userId"/>
            </div> --}}

            {{-- form edit data user general--}}
            {{-- <div
                v-cloak
                v-show="currentView === 'edit'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    @include('users._card-form-edit')
            </div> --}}

            {{-- card show data user detail --}}
            {{-- <div
                v-cloak
                v-show="currentView === 'detail'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                @include('users._card-detail-user')
            </div> --}}

            {{-- card table data user --}}
            {{-- <div
                v-show="currentView === 'table'"
                class="relative shadow-md sm:rounded-lg">
                @include('users._card-table-user')
            </div> --}}
            {{-- <div
              v-show="currentView === 'table'"
            >
            <pagination :data-user="links" @paginate="getListUser" />
            </div> --}}
        </div>
    </div>
</x-layouts.app>
