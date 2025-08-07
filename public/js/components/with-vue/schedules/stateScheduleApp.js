    import dataTableSchedule from "./dataTableSchedule.js"
    import formCreateTime from "./formCreateTime.js"
    import cardListTeacherSubject from "./cardListTeacherSubject.js"
    import settingSchedule from "./settingSchedule.js"
    const { createApp, ref, reactive, onMounted } = Vue

export default function stateScheduleApp () {
    createApp({
        components: { dataTableSchedule, formCreateTime, cardListTeacherSubject, settingSchedule },
        setup() {
            const currentView = ref("table")
            const disableButton = ref(false)
            const isLoading = ref(false)
            const listTimetable = ref([])
            const listWeekDays = ref({})
            const handleCreateTimetable = ()=> { currentView.value = 'create-time' }

            onMounted(async ()=> {
                await getListTimetable()
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

            async function refreshTimeTable() {
                await getListTimetable()
            }

            function handleSettingSchedule(data) {
                currentView.value = data.component
                console.log('subjectId', data.subjectId)
            }

            return {
                currentView, handleCreateTimetable, isLoading, getListTimetable, listTimetable, refreshTimeTable, listWeekDays,
                handleSettingSchedule
            }
        },
        template: `
            <!-- komponent untuk data-table-schedule -->
            <div v-show="currentView === 'table'" class="relative shadow-md sm:rounded-lg">
                <data-table-schedule
                    :dataProvide="listTimetable"
                    @add-time="handleCreateTimetable"
                    :weekProvide="listWeekDays"
                />
            </div>

            <!-- komponent untuk creaet-time-schedule -->
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
