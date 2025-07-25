const { defineComponent, ref, watch, computed } = Vue

export default defineComponent({
    name: 'dataTableMajor',
    props: {
        dataProvide: {
            type: Array,
            required: true
        }
    },
    emits: ['reload'],
    setup(props, {emit}) {
        const editMajor = (majorId)=> {

        }

        const deleteConfirmation = (majorId)=> {
             confirmDelete('Yakin dihapus?', async (result)=>{
                if(!result.isConfirmed) {
                    return
                }
                await swalLoading('delete process..',async (result)=> {
                    try {
                        let result = await axios.delete(`/delete-major/${majorId}`)
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
            editMajor, deleteConfirmation
        }
    },
    template: `
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            #
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Jurusan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Slug Jurusan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Inisial Jurusan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Kepala Jurusan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                     <template v-if="dataProvide.length > 0">
                        <tr v-for="(major, index) in dataProvide" :key="major.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4"> {{index + 1}} </td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{major.name}}
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{major.slug}}
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{major.initial}}
                            </th>
                            <td class="px-2 py-4">
                                <template v-if="major.head_major && major.head_major.id">
                                    <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-green-400 border border-green-400">
                                        {{major.head_major.name}}
                                    </span>
                                </template>
                            </td>
                            <th scope="row"
                                class="px-6 py-4 flex gap-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <a @click="editMajor(major.id)"
                                    class="cursor-pointer font-medium text-gray-600 dark:text-gray-500 hover:underline">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </a>
                                <a @click="deleteConfirmation(major.id)"
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
});
