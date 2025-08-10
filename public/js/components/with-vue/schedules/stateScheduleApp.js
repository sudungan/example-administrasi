    import dataTableSchedule from "./dataTableSchedule.js"
    import formCreateTime from "./formCreateTime.js"
    import cardListTeacherSubject from "./cardListTeacherSubject.js"
    import settingSchedule from "./settingSchedule.js"
    import cardSettingTimeTable from "./cardSettingTimeTable.js"
    import cardCreateSchedule from "./cardCreateSchedule.js"
    const { createApp, ref, reactive, onMounted } = Vue

export default function stateScheduleApp () {
    createApp({
        components: { dataTableSchedule, formCreateTime, cardListTeacherSubject, settingSchedule, cardSettingTimeTable,
            cardCreateSchedule,
         },
        setup() {
            const currentView = ref("table")
            const isLoading = ref(false)
            const listTimetable = ref([])
            const listClassroom = ref([])
            const editTimeSlot = ref({ id: '', start_time: '',  end_time: '', activity: '', category: '' })
            const handleCreateTimetable = ()=> { currentView.value = 'create-time' }
            const showFormCreate =()=> currentView.value = 'create'
            const days = [
                {id: 1, value: 'senin', label: 'senin'},
                {id: 2, value: 'selasa', label: 'selasa'},
                {id: 3, value: 'rabu', label: 'rabu'},
                {id: 4, value: 'kamis', label: 'kamis'},
                {id: 5, value: 'jumat', label: 'jumat'},
            ]

            onMounted(async ()=> {
                await getListTimetable()
                await getListClassroom()
            });
            async function getListTimetable() {
                try {
                    let result = await axios.get('/list-timetable')
                    listTimetable.value = result.data.data
                } catch (error) {
                    console.log('error', error)
                }
            }


            async function getListClassroom() {
                try {
                    const result = await axios.get('/list-classroom');
                    listClassroom.value = result.data.data
                } catch (error) {
                    console.log(error)
                }
            }

            async function refreshTimeTable() {
                await getListTimetable()
            }

            // fungsi untuk setting mata pelajaran berdasarkan mapel by subjectId
            function handleSettingSchedule(data) {
                currentView.value = data.component
                console.log('subjectId', data.subjectId)
            }

            // fungsi untuk melakukan pengambilan data time slot by Id dan akan dipassing ke card
            async function getEditTimeSlot(data) {
                try {
                    let result = await axios.get(`/time-slot-by/${data.timeId}`)
                    editTimeSlot.value = result.data.data;
                    currentView.value = data.component // mengambil nama component
                } catch (error) {
                    console.log('error mengambil data time-slot', error)
                }
            }



            return {
                currentView, handleCreateTimetable, isLoading, getListTimetable, listTimetable, refreshTimeTable,
                handleSettingSchedule, editTimeSlot, getEditTimeSlot, listClassroom, getListClassroom, days, showFormCreate
            }
        },
        template: `

        <!-- komponent untuk tombol-create-schedule -->
        <div class="flex gap-4">
            <button
                v-show="currentView === 'table'"
                @click="showFormCreate"
                type="button"
                class="text-white mb-2 inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 text-center text-xs dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <svg class="me-1 ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                Jadwal
            </button>
        </div>

            <!-- komponent untuk data-table-schedule -->
            <div v-show="currentView === 'table'" class="relative shadow-md sm:rounded-lg">
                <data-table-schedule
                    :dataProvide="listTimetable"
                    :provideClassroom="listClassroom"
                    @add-time="handleCreateTimetable"
                    @edit-time="getEditTimeSlot"
                    @reload="refreshTimeTable"
                />
            </div>

            <!-- komponent untuk create-time-schedule -->
            <div
                v-cloak
                v-show="currentView === 'create-time'"
                class="relative sm:rounded-lg">
                <form-create-time
                    :visable-card="currentView"
                    :waiting-process="isLoading"
                    @reload="refreshTimeTable"
                    @back-to="currentView = $event"
                />
            </div>

             <!-- komponent untuk setting-time-schedule -->
            <div
                v-cloak
                v-show="currentView === 'card-setting-time'"
                class="relative sm:rounded-lg">
                <card-setting-time-table
                    :visable-card="currentView"
                    :waiting-process="isLoading"
                    :provide-data="editTimeSlot"
                    @back-to="currentView = $event"
                    @reload="refreshTimeTable"
                />
            </div>

             <!-- komponent untuk card-setting-schedule -->
            <div
                v-cloak
                v-show="currentView === 'setting-schedule'"
                class="relative sm:rounded-lg mt-6">
                <settingSchedule
                    @back-to="currentView = $event"
                    :provide-days="days"
                />
            </div>

             <!-- komponent untuk card-create-schedule -->
            <div
                v-if="currentView === 'create'"
                class="relative sm:rounded-lg mt-6">
                <card-create-schedule
                    @back-to="currentView = $event"
                    :provide-days="days"
                    :waiting-process="isLoading"
                />
            </div>
        `
    }).mount('#app')
}
