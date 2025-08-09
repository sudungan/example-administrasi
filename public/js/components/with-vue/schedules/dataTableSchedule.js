const { defineComponent, ref, watch, computed } = Vue
export default defineComponent({
    name: 'dataTableSchedule', // nama child component
    props: {
        dataProvide: {
            type: Array,
            required: true
        },
        provideClassroom: {
            type: Array,
            required: true
        },
    },
    emits: ['addTime', 'editTime', 'reload'],
    setup(props, {emit}) {
        const childListTimetable = ref([])
        const hoveredTimeId = ref(null)
        const childListClassroom = ref(props.provideClassroom)
        const days = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT']


        watch(()=> props.dataProvide, (newVal) => { childListTimetable.value = [...newVal] }, { immediate: true });
        watch(()=> props.provideClassroom, (newVal) => { childListClassroom.value = [...newVal] }, { immediate: true });

        const formatTime = (time) => time.slice(0, 5);
        const btnAddTime =()=> {  emit('addTime')   }

        function btnEditTimeSlot(timeId) {
            emit('editTime', {component: 'card-setting-time', timeId: timeId})
        }

        function btnDeleteTimeSlot(timeId) {
            console.log('timeId', timeId)
             confirmDelete('Yakin dihapus?', async (result)=>{
                if(!result.isConfirmed) {
                    return
                }
                await swalLoading('deleting process..',async (result)=> {
                    try {
                        let result = await axios.delete(`/delete-time-slot-by/${timeId}`)
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
            btnAddTime, childListTimetable, formatTime, btnEditTimeSlot, childListClassroom, btnDeleteTimeSlot, days,
            hoveredTimeId
        }
    },
    template: `

<div class="relative overflow-x-auto border-gray-600 shadow-md sm:rounded-lg">
    <table border="1" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-white uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th rowspan="2" class="w-fit border border-r border-gray-600 rounded text-center align-middle cursor-pointer">
                    <div class="h-full flex flex-col justify-center items-center divide-y divide-gray-400">
                        <span @click="btnAddTime" class="w-full py-1 dark:text-gray-400 text-gray-600 hover:underline hover:font-bold hover:text-green-600">Waktu</span>
                        <span class="w-full py-1 text-gray-600 dark:text-gray-400">Kelas</span>
                    </div>
                </th>
                <th v-for="(day, index) in days" :colspan="childListClassroom.length" :key="day"
                    :class="[
                        'border border-r text-center border-gray-200 text-gray-400 font-bold dark:border-gray-400 border-2 rounded-sm',
                        index % 2 === 0 ? 'bg-yellow-300' : 'bg-orange-300'
                    ]"> {{ day }}
                </th>
            </tr>
            <tr>
                <template v-for="day in days" :key="day">
                    <th v-for="classroom in childListClassroom" :key="day + classroom" class="border border-r text-center border-gray-600 font-bold dark:border-gray-600 border-1 dark:border-2 dark:text-gray-400 text-gray-800 rounded-lg">{{ classroom.name }}-{{classroom.major?.initial}}</th>
                </template>
            </tr>
        </thead>
        <tbody>
            <template v-if="childListTimetable.length > 0">
                <template v-for="time in childListTimetable" :key="childListTimetable.id" >
                    <tr class="w-fit bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 border-b">
                        <th class="w-fit px-1 py-1 text-start flex-inline"  @mouseenter="hoveredTimeId = time.id" @mouseleave="hoveredTimeId = null">
                            <span id="badge-dismiss-default" class="inline-flex items-center justify-center bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2 py-1 rounded-sm dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                                {{ formatTime(time.start_time) }} - {{ formatTime(time.end_time) }}
                                <button
                                    v-show="hoveredTimeId === time.id"
                                    @click="btnEditTimeSlot(time.id)"
                                    type="button"
                                    class="cursor-pointer inline-flex items-center p-1 ms-2 text-sm text-blue-400 bg-transparent rounded-xs hover:bg-blue-200 hover:text-blue-900 dark:hover:bg-blue-800 dark:hover:text-blue-300" data-dismiss-target="#badge-dismiss-default" aria-label="Remove">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="gray" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                <button
                                    v-show="hoveredTimeId === time.id"
                                    @click="btnDeleteTimeSlot(time.id)"
                                    type="button"
                                    class="cursor-pointer inline-flex items-center p-1 ms-2 text-sm text-blue-400 bg-transparent rounded-xs hover:bg-blue-200 hover:text-blue-900 dark:hover:bg-blue-800 dark:hover:text-blue-300" data-dismiss-target="#badge-dismiss-default" aria-label="Remove">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="orange" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </span>
                        </th>
                    </tr>
                </template>
            </template>
        </tbody>
    </table>
</div>

    `
})
