const { defineComponent, ref, watch, computed, onMounted } = Vue

export default defineComponent({
    name: 'dataTableVocationalExam',
    props: {
        visableCard: {
            type: String,
            required: true
        },
        dataProvideBy: {
            type: Array,
            required: true
        }
    },
    emits: [''],
    setup(props, {emit}) {},
    template: `
    <div class="mt-2 relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex flex-wrap justify-between pb-4">
             <div></div>
                <!-- Search Input di Pojok Kanan -->
                <div class="relative">
                    <label for="table-search" class="sr-only">Search</label>
                    <div class="absolute inset-y-0 left-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input type="text" id="table-search"
                        class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Search for items">
                </div>
            </div>

           <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class=" bg-gray-200 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            #
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Ujian
                        </th>
                            <th scope="col" class="px-6 py-3">
                            Periode
                        </th>
                            <th scope="col" class="px-6 py-3">
                           Tema
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- start empty data exam -->
                    <template v-if="dataProvideBy.length == 0">
                        <tr>
                            <td colspan="100%" class="text-center text-gray-500">
                                <div class="p-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
                                    <span class="font-medium">Data Ujian Kejuruan Belum ada..</span> 
                                </div>
                            </td>
                        </tr>
                    </template>
                    <!-- start empty data exam -->

                    <!-- start empty data exam -->
                    <!-- start has data exam -->
                    <template v-for="(vocationalExam, index) in dataProvideBy" :key="vocationalExam.id">
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ index + 1 }}
                            </th>
                            <td class="px-6 py-4">
                                {{ vocationalExam.name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ vocationalExam.period }}
                            </td>
                            <td class="px-6 py-4">
                               {{ vocationalExam.description }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    `
})
