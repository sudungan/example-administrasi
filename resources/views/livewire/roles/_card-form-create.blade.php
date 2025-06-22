<div class="relative p-4 w-full max-w-2xl max-h-full">
    <!-- Modal content -->
    <div class="relative bg-gray-300 bg-gray-100 rounded-lg shadow-sm dark:bg-gray-900">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white dark:semibold">
                Form Addition Role
            </h3>
        </div>
        <!-- Modal body -->
        <div class="p-4 md:p-5 space-y-4">
            <form @submit.prevent="sendDataRole" class="space-y-4">
            <div class="col-span-2 sm:col-span-1">
                    <select wire:model.live="role_id" id="role_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
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
            <div>
                <label for="name" class="block dark:text-white mb-2 text-sm font-medium text-gray-900">Jabatan Tambahan</label>
                <input type="text"  x-model="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="write addition role name here.." />
                    @error('name')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
            </div>

            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 border-gray-200 rounded-b dark:border-gray-600">
                <button
                    type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-400 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Save
                </button>
                <button
                    @click="closeFormCreate"
                    type="button"
                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-gray-200 rounded-lg border border-gray-200 hover:bg-gray-600 hover:text-blue-200 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</button>
            </div>
        </form>
        </div>
    </div>
    </div>
