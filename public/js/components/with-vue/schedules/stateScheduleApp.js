    import dataTableSchedule from "./dataTableSchedule.js"
    import formCreateTime from "./formCreateTime.js"
    const { createApp, ref, reactive, onMounted } = Vue

export default function stateScheduleApp () {
    createApp({
        components: { dataTableSchedule, formCreateTime },
        setup() {
            const currentView = ref("table")
            const disableButton = ref(false)
             const isLoading = ref(false)
            const handleCreateTimetable = ()=> { currentView.value = 'create-time' }
            return {
                currentView, handleCreateTimetable, isLoading
            }
        },
        template: `
            <!-- komponent untuk data-table-schedule -->
            <div v-show="currentView === 'table'" class="relative shadow-md sm:rounded-lg">
                <data-table-schedule
                    @add-time="handleCreateTimetable"
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
                    @back-to="currentView = $event"
                />
            </div>
        `
    }).mount('#app')
}
