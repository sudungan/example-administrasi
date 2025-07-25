import baseLoadingTable from "../../baseLoadingTable.js"
const { defineComponent } = Vue
export default defineComponent({
    name: 'loadingTableMajor', // nama child component
    components: {
        baseLoadingTable
    },
    template:  `
       <base-loading-table>
            <template #thead>
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">NAMA JURUSAN</th>
                    <th class="px-6 py-3">SLUG JURUSAN</th>
                    <th class="px-6 py-3">WALI KELAS</th>
                    <th class="px-6 py-3">Action</th>
                </tr>
                </thead>
            </template>
            <template #skeleton>
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
                <td class="px-6 py-4 flex gap-2">
                    <div class="h-2.5 bg-gray-300 rounded w-6"></div>
                    <div class="h-2.5 bg-gray-300 rounded w-6"></div>
                    <div class="h-2.5 bg-gray-300 rounded w-6"></div>
                </td>
            </template>
       </base-loading-table>
    `
})
