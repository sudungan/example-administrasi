<div role="status" class="w-full rounded-lg overflow-x-auto">
    <button
        type="button"
        class="text-white animate-pulse mb-2 inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
        role
    </button>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 animate-pulse">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-6 py-3">#</th>
                <th class="px-6 py-3">Nama Jabatan</th>
                <th class="px-6 py-3">Jabatan Tambahan</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="n in 5" :key="n">
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="px-6 py-4">
                        <div class="h-2.5 bg-gray-300 rounded w-5"></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="h-2.5 bg-gray-300 rounded w-24"></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="h-2.5 bg-gray-300 rounded w-28"></div>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
    <span class="sr-only">Loading...</span>
</div>
