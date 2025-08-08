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
        weekProvide: {
            type: Object,
            required: true
        }
    },
    emits: ['addTime', 'editTime'],
    setup(props, {emit}) {
        const childListTimetable = ref([])
        const childListWeekDays = ref(props.weekProvide)
        const childListClassroom = ref(props.provideClassroom)
        const days = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT']


        watch(()=> props.dataProvide, (newVal) => { childListTimetable.value = [...newVal] }, { immediate: true });
        watch(()=> props.weekProvide, (newVal) => { childListWeekDays.value = newVal }, { immediate: true });
        watch(()=> props.provideClassroom, (newVal) => { childListClassroom.value = [...newVal] }, { immediate: true });

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
            btnAddTime, childListTimetable, formatTime, childListWeekDays, btnEditTimetable, childListClassroom, days
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
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 border-b">
                        <th class="px-1 py-1 text-center align-middle">
                            <span @click="btnEditTimetable(time.id)" class="hover:underline hover:cursor-pointer bg-blue-100 text-blue-800 text-[10px] font-medium px-1.5 py-0.5 rounded-sm border border-blue-400 dark:bg-gray-700 dark:text-blue-400">
                                {{ formatTime(time.start_time) }} - {{ formatTime(time.end_time) }}
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
