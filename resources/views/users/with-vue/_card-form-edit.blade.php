<div class="relative p-4 w-full max-w-3xl max-h-full">
    <div class="relative bg-gray-300 rounded-lg shadow-sm dark:bg-gray-900">
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white dark:semibold">
                Form Edit Data Umum
            </h3>
        </div>
        <div class="p-4 md:p-5 space-y-4">
            <form @submit.prevent="storeDataUserGeneral" class="space-y-4">
                <div class="grid gap-2 mb-4 grid-cols-2">
                    <div class="col-span-2 sm:col-span-1">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama User</label>
                        <input
                            type="text"
                            v-model="user.name"
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
                            v-model="user.email"
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
                        {{-- viewable --}}
                    />

                    <div class="col-span-2 sm:col-span-1">
                        <label for="role_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan Utama</label>
                        <select v-model="user.role_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option :value="user.role?.name" disabled>@{{user.role?.name}} </option>
                            <option v-for="role in listRole" :key="role.id" :value="role.id">@{{role.name}}</option>
                        </select>
                            @error('role_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                        </select>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Simpan
                        </button>
                        <button
                            type="button"
                            @click="showTable"
                            class="text-white inline-flex items-center bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                        cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
