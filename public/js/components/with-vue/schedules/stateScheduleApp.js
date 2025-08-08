    import dataTableSchedule from "./dataTableSchedule.js"
    import formCreateTime from "./formCreateTime.js"
    import cardListTeacherSubject from "./cardListTeacherSubject.js"
    import settingSchedule from "./settingSchedule.js"
    import cardSettingTimeTable from "./cardSettingTimeTable.js"
    const { createApp, ref, reactive, onMounted } = Vue

export default function stateScheduleApp () {
    createApp({
        components: { dataTableSchedule, formCreateTime, cardListTeacherSubject, settingSchedule, cardSettingTimeTable },
        setup() {
            const currentView = ref("table")
            const isLoading = ref(false)
            const listTimetable = ref([])
            const listWeekDays = ref({})
            const listClassroom = ref([])
            const editTimeSlot = ref({ id: '', start_time: '',  end_time: '', activity: '', category: '' })
            const handleCreateTimetable = ()=> { currentView.value = 'create-time' }

            onMounted(async ()=> {
                await getListTimetable()
                await getListClassroom()
            });
            async function getListTimetable() {
                try {
                    let result = await axios.get('/list-timetable')
                    listTimetable.value = result.data.data
                    listWeekDays.value = result.data.weekdays
                    console.log('data', listWeekDays.value)
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
                currentView, handleCreateTimetable, isLoading, getListTimetable, listTimetable, refreshTimeTable, listWeekDays,
                handleSettingSchedule, editTimeSlot, getEditTimeSlot, listClassroom, getListClassroom
            }
        },
        template: `
            <!-- komponent untuk data-table-schedule -->
            <div v-show="currentView === 'table'" class="relative shadow-md sm:rounded-lg">
                <data-table-schedule
                    :dataProvide="listTimetable"
                    :provideClassroom="listClassroom"
                    @add-time="handleCreateTimetable"
                    @edit-time="getEditTimeSlot"
                    :weekProvide="listWeekDays"
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

            <!-- komponent untuk data-table-schedule -->
            <div
                v-cloak
                v-show="currentView === 'table'"
                class="relative sm:rounded-lg mt-6">
                <card-list-teacher-subject
                    @setting="handleSettingSchedule"
                />
            </div>

             <!-- komponent untuk card-setting-schedule -->
            <div
                v-cloak
                v-show="currentView === 'setting-schedule'"
                class="relative sm:rounded-lg mt-6">
                <settingSchedule
                    @back-to="currentView = $event"
                />
            </div>
        `
    }).mount('#app')
}
