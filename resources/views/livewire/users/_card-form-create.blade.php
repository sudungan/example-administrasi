<div class="relative w-full max-w-4xl max-h-full">
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
            <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                Form Data Umum
            </h3>
        </div>
        <!-- Modal body -->
        <div class="p-4 md:p-5 space-y-4">
                 <form wire:submit="sendDataUser" class="p-2 md:p-3">
                <div class="grid gap-2 mb-4 grid-cols-2">
                    <div class="col-span-2 sm:col-span-1">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama User</label>
                        <input
                            type="text"
                            wire:model="name"
                            id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
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
                            wire:model="email"
                            id="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="write your email here.."
                        >
                         @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <flux:input
                            wire:model="password"
                            :label="__('Password')"
                            type="password"
                            :placeholder="__('Password')"
                             autocomplete="new-password"
                            viewable
                    />

                     <div class="col-span-2 sm:col-span-1">
                        <label for="role_user" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan / Role</label>
                         <select wire:model.live="role_user" id="role_user" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected="">Select category</option>
                                @forelse ($mainRole as $key => $value)
                                    @if ($key != 'admin')
                                        <option value="{{ $value }}">{{ $key }}</option>
                                    @endif
                                @endforeach
                        </select>
                         @error('role_user')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- <div class="col-span-2 sm:col-span-1">
                        <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Depan</label>
                        <input
                            type="first_name"
                            wire:model="first_name"
                            id="first_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="write your firstname here.."
                        >
                         @error('first_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Belakang</label>
                        <input
                            type="text"
                            wire:model="last_name"
                            id="last_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Type your last name.."
                        >
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat Tempat Tinggal</label>
                        <input
                            type="text"
                            wire:model="address"
                            id="address"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="write your address here.."
                        >
                         @error('address')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="role_user" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan / Role</label>
                         <select wire:model.live="role_user" id="role_user" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected="">Select category</option>
                                @forelse ($mainRole as $key => $value)
                                    @if ($key != 'admin')
                                        <option value="{{ $value }}">{{ $key }}</option>
                                    @endif
                                @endforeach
                        </select>
                         @error('role_user')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                     <div wire:show="selectClassroom" class="col-span-2 sm:col-span-1">
                        <label for="major_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Jurusan</label>
                        <select wire:model.live="major_id" id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="" selected="">Select major</option>
                                @forelse ($listMajor as $major)
                                    <option value="{{ $major->id }}">{{ $major->name }}</option>
                                @empty
                                    <option disabled class="font-normal bg-yellow-400 hover:font-bold capitalize">Data Jurusan Belum Tersedia..</option>
                                @endforelse
                        </select>
                         @error('major_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div wire:show="selectClassroom" class="col-span-2 sm:col-span-1">
                        <label for="classroom_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kelas</label>
                         <select wire:model="classroom_id" id="classroom_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected="">Select classroom</option>
                                @forelse ($listClassroom as $classroom)
                                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                @empty
                                    <option disabled class="font-normal bg-yellow-400 hover:font-bold capitalize">Data kelas Belum Tersedia..</option>
                                @endforelse
                        </select>
                        @error('classroom_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div> --}}
                <div class="flex gap-4">
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        new User
                    </button>
                    <button
                        type="button"
                        @click="closeModalForm"
                        class="text-white inline-flex items-center bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                       cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- <div class="relative p-2 w-full max-w-4xl max-h-full">
    <div class="relative bg-white rounded-lg shadow-sm bg-gray-900">
        <div class="p-2 md:p-3 space-y-4">
            <form wire:submit="sendDataUser" class="p-2 md:p-3">
                <div class="grid gap-2 mb-4 grid-cols-2">
                    <div class="col-span-2 sm:col-span-1">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama User</label>
                        <input
                            type="text"
                            wire:model="name"
                            id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
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
                            wire:model="email"
                            id="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="write your email here.."
                        >
                         @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                     <div class="col-span-2 sm:col-span-1">
                        <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone number</label>
                        <input
                            type="tel"
                            wire:model="phone_number"
                            id="phone_number"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Type your phone number here.."
                        >
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <flux:input
                            wire:model="password"
                            :label="__('Password')"
                            type="password"
                            :placeholder="__('Password')"
                             autocomplete="new-password"
                            viewable
                    />
                    <div class="col-span-2 sm:col-span-1">
                        <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Depan</label>
                        <input
                            type="first_name"
                            wire:model="first_name"
                            id="first_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="write your firstname here.."
                        >
                         @error('first_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Belakang</label>
                        <input
                            type="text"
                            wire:model="last_name"
                            id="last_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Type your last name.."
                        >
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat Tempat Tinggal</label>
                        <input
                            type="text"
                            wire:model="address"
                            id="address"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="write your address here.."
                        >
                         @error('address')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="role_user" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan / Role</label>
                         <select wire:model.live="role_user" id="role_user" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected="">Select category</option>
                                @forelse ($mainRole as $key => $value)
                                    @if ($key != 'admin')
                                        <option value="{{ $value }}">{{ $key }}</option>
                                    @endif
                                @endforeach
                        </select>
                         @error('role_user')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                     <div wire:show="selectClassroom" class="col-span-2 sm:col-span-1">
                        <label for="major_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Jurusan</label>
                        <select wire:model.live="major_id" id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="" selected="">Select major</option>
                                @forelse ($listMajor as $major)
                                    <option value="{{ $major->id }}">{{ $major->name }}</option>
                                @empty
                                    <option disabled class="font-normal bg-yellow-400 hover:font-bold capitalize">Data Jurusan Belum Tersedia..</option>
                                @endforelse
                        </select>
                         @error('major_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div wire:show="selectClassroom" class="col-span-2 sm:col-span-1">
                        <label for="classroom_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kelas</label>
                         <select wire:model="classroom_id" id="classroom_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected="">Select classroom</option>
                                @forelse ($listClassroom as $classroom)
                                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                @empty
                                    <option disabled class="font-normal bg-yellow-400 hover:font-bold capitalize">Data kelas Belum Tersedia..</option>
                                @endforelse
                        </select>
                        @error('classroom_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        new User
                    </button>
                    <button
                        type="button"
                        @click="closeModal"
                        class="text-white inline-flex items-center bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                       cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> --}}


