const { defineComponent, ref, watch, computed } = Vue
export default defineComponent({
    name: 'dataTableSchedule', // nama child component
    props: {
        dataProvide: {
            type: Array,
            required: true
        },
        weekProvide: {
            type: Object,
            required: true
        }
    },
    emits: ['addTime', 'editTime'],
    setup(props, {emit}) {
        const childListTimetable = ref([])
        const childListWeekDays = ref(props.weekProvide)
        watch(()=> props.dataProvide, (newVal) => { childListTimetable.value = [...newVal] }, { immediate: true });
        watch(()=> props.weekProvide, (newVal) => { childListWeekDays.value = newVal }, { immediate: true });

        let dummyListClassroom = [
            {id: 1, name: 'X-AK'}, {id: 2, name: 'X-RPL' }, {id: 3, name: 'XI-AK'}, {id: 4, name: 'XI-RPL'}, {id: 5, name: 'XII-AK', },
        ]
        const formatTime = (time) => time.slice(0, 5);
        const btnAddTime =()=> {
            emit('addTime')
        }

        function btnEditTimetable(timeId) {
            emit('editTime', {component: 'card-setting-time', timeId: timeId})
        }

        return {
            btnAddTime, childListTimetable, formatTime, childListWeekDays, btnEditTimetable
        }
    },
    template: `

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-white uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th @click="btnAddTime" class="px-2 py-2 text-start border-r border-gray-300">
                    <div class="flex flex-col items-start">
                        <span class="underline cursor-pointer hover:font-bold hover:text-red-900">Time</span>
                        <span>Kelas</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-3 border-r border-gray-300 text-center">
                    {{ childListWeekDays.senin }}
                </th>
                <th scope="col" class="px-6 py-3 border-r border-gray-300">
                    {{ childListWeekDays.selasa }}
                </th>
                <th scope="col" class="px-6 py-3 border-r border-gray-300">
                  {{ childListWeekDays.rabu }}
                </th>
                <th scope="col" class="px-6 py-3 border-r border-gray-300">
                   {{ childListWeekDays.kamis }}
                </th>
                <th scope="col" class="px-6 py-3 border-r border-gray-300">
                   {{ childListWeekDays.jumat }}
                </th>
            </tr>
        </thead>
        <tbody>
            <template v-if="childListTimetable.length > 0">
                <template v-for="time in childListTimetable" :key="childListTimetable.id" >
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 border-b">
                        <th class="px-1 py-1">
                            <span @click="btnEditTimetable(time.id)" class="hover:underline hover:cursor-pointer bg-blue-100 text-blue-800 text-[10px] font-medium px-1.5 py-0.5 rounded-sm border border-blue-400 dark:bg-gray-700 dark:text-blue-400">
                                {{ formatTime(time.start_time) }} - {{ formatTime(time.end_time) }}
                            </span>
                        </th>
                        <td class="px-6 py-4">
                        Silver
                        </td>
                        <td class="px-2 py-4 bg-blue-500">
                        Laptop
                        </td>
                        <td class="px-6 py-4">
                        $2999
                        </td>
                        <td class="px-6 py-4 bg-blue-500">
                        <a href="#" class="font-medium text-white hover:underline">Edit</a>
                        </td>
                    </tr>
                </template>
            </template>
        </tbody>
    </table>
</div>

    `
})
