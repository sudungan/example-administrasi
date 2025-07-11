const { defineComponent } = Vue
export default defineComponent({
    name: 'loadingTable', // nama child component
    template:  `
        <div role="status" class="w-full overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 animate-pulse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Nama Jurusan</th>
                        <th class="px-6 py-3">Slug Jurusan</th>
                        <th class="px-6 py-3">Inisial Jurusan</th>
                        <th class="px-6 py-3">Kepala Jurusan</th>
                        <th class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="n in 5" :key="n">
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
                            <td class="px-6 py-4">
                                <div class="h-2.5 bg-gray-300 rounded w-32"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-2.5 bg-gray-300 rounded w-32"></div>
                            </td>
                            <td class="px-6 py-4 flex gap-2">
                                <div class="h-2.5 bg-gray-300 rounded w-6"></div>
                                <div class="h-2.5 bg-gray-300 rounded w-6"></div>
                                <div class="h-2.5 bg-gray-300 rounded w-6"></div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <span class="sr-only">Loading...</span>
        </div>
    `
})
