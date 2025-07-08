 <form class="space-y-4 bg-white dark:bg-gray-800">
    <fieldset class="relative border border-gray-300 dark:border-gray-600 rounded-md p-6">
        <legend class="absolute -top-3 left-4 bg-white dark:bg-gray-800 px-2 text-sm font-semibold text-gray-700 dark:text-gray-200">
        Data Profile
        </legend>

        <div class="grid gap-2 mb-4 grid-cols-2">
        <div class="col-span-2 sm:col-span-1">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Depan</label>
            <input
            type="text"
            id="name"
            :value="dataUserProfile?.first_name"
            readonly
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
            placeholder="Type username"
            >
        </div>

        <div class="col-span-2 sm:col-span-1">
            <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Depan</label>
            <input
            type="text"
            :value="dataUserProfile?.last_name"
            id="last_name"
            readonly
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
            placeholder="example@example.com"
            >
        </div>

        <div class="col-span-2 sm:col-span-1">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
            <input
            type="password ?? text"
            :value="user.password"
            id="password"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
            placeholder="******"
            readonly
            >
        </div>

        <div class="col-span-2 sm:col-span-1">
            <label for="role_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan Utama</label>
            <select disabled id="role_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option>@{{ user.role?.name }}</option>
            </select>
        </div>
        </div>
    </fieldset>
</form>
