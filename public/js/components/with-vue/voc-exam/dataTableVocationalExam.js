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
    emits: ['reload'],
    setup(props, {emit}) {
        function btnEditVocationalExam(examId) {
            emit('edit', examId)
        }

        function deleteConfirmation(examId) {
            confirmDelete('Yakin dihapus?', async (result)=>{
                if(!result.isConfirmed) {
                    return
                }
                await swalLoading('delete process..',async (result)=> {
                    try {
                        let result = await axios.delete(`/destroy-vocational-exam/${examId}`)
                        successNotification(result.data.message)
                        emit('reload')
                    } catch (error) {
                        if (error.response && error.response.status == 409) {
                            swalNotificationConflict(error.response.data.message)
                        }else {
                            swalInternalServerError(error.response.data.message) // http code 500
                        }
                    }
                });
            })
        }
        return {
            btnEditVocationalExam, deleteConfirmation
        }
    },
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
                            <th scope="row"
                                class="px-6 py-4 flex gap-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <a @click="btnEditVocationalExam(vocationalExam.id)"
                                    class="cursor-pointer font-medium text-gray-600 dark:text-gray-500 hover:underline">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </a>
                                <a @click="deleteConfirmation(vocationalExam.id)"
                                    class="cursor-pointer font-medium text-red-600 dark:text-red-500 hover:underline">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </a>
                            </th>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    `
})
