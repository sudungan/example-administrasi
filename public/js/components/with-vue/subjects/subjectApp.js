    import dataTableSubject from "./dataTableSubject.js"
    import formCreateSubject from "./formCreateSubject.js"
    import loadingTableSubject from "./loadingTableSubject.js"
    import createTeacherColour from "./createTeacherColour.js"
    const { createApp, ref, reactive, onMounted } = Vue
export default function subjectApp () {

    createApp({
        components: {
            dataTableSubject,
            formCreateSubject,
            loadingTableSubject,
            createTeacherColour,
        },
        setup() {
            const currentView = ref("loading-table")
            const message = ref('Hello Vue!')
            const listTeacherSubject = ref([])
            const selectTeacherId = ref(null)
            const hasTeacherBaseColour = ref(null)
            const dataTeacherBy = ref({ id: '', name: '' })
            const dataTeacher = ref({})
            const listSubject = ref([])
            const listTeacher = ref([])
            const listClassroom = ref([])
            const showFormCreate =()=> currentView.value = 'create-teacher-colour'
            const baseColour = ref([
                { id:'1', item:'blue'}, { id:'2', item:'gray'}, { id:'3', item:'red'}, { id:'4', item:'green'}, { id:'5', item:'yellow'},
                { id:'6', item:'indigo'}, { id:'7', item:'purple'}, { id:'8', item:'pink'}, { id:'9', item:'lime'}, { id:'10', item:'indigo'},
                { id:'11', item:'rose'}, { id:'12', item:'cyan'}, { id:'13', item:'emerald'}, { id:'14', item:'violet'}, { id:'15', item:'sky'},
            ])

            const baseCssColour = {
                blue: '#3b82f6',
                gray: '#6b7280',
                red: '#ef4444',
                green: '#10b981',
                yellow: '#facc15',
                indigo: '#6366f1',
                purple: '#8b5cf6',
                pink: '#ec4899',
                lime: '#84cc16',
                rose: '#f43f5e',
                cyan: '#06b6d4',
                emerald: '#10b981',
                violet: '#8b5cf6',
                sky: '#0ea5e9'
            }

            onMounted(async ()=>{
                await getListBaseTeacherColour()
                await getListSubject()
                await getListTeacher()
                await getListTeacherSubject()
                await getListClassroom()
            });

            async function getListSubject() {
                try {
                    currentView.value = 'loading-table'
                    let result = await axios.get('list-subject');
                    listSubject.value = result.data.data
                    currentView.value = 'table'
                } catch (error) {
                    console.log('error:', error)
                }
            }

            async function getListTeacherSubject() {
                try {
                    let result = await axios.get('list-teacher-subject');
                    listTeacherSubject.value = result.data.data
                    currentView.value = 'table'

                } catch (error) {
                    console.log('error:', error)
                }
            }

            async function refreshListSubject() {
                getListSubject()
            }

            async function getListBaseTeacherColour() {
                try {
                    let result = await axios.get('/check-base-colour-teacher')
                    console.log('data', result)
                } catch (error) {
                    console.log(error)
                }
            }

            async function getListTeacher() {
                try {
                    let result = await axios.get('list-teacher');
                    listTeacher.value = result.data.data
                } catch (error) {
                    console.log('error:', error)
                }
            }

            // fungsi untuk menghandle passing data dari BE ke component lain
            function handlePassingData(data) {
                dataTeacher.value = data
            }

            async function handleSelectTeacher(data) {
                try {
                    selectTeacherId.value = data.teacherId
                    dataTeacherBy.value = await getTeacherBy(data.teacherId)
                    hasTeacherBaseColour.value =  await checkBaseColour(selectTeacherId.value)
                    if (hasTeacherBaseColour.value) {
                        currentView.value = data.component   // 'create-subject'
                    }else {
                        currentView.value = 'create-teacher-colour'
                        dataTeacherBy.value
                    }
                } catch (error) {
                    console.log('error', error)
                }
            }

            async function checkBaseColour(teacherId) {
                try {
                    let result = await axios.get(`/check-base-colour-by/${teacherId}`)
                    return result.data.data
                } catch (error) {
                    console.log('error:', error)
                }

            }

            async function getTeacherBy(teacherId) {
                try {
                    let result = await axios.get(`/teacther-by/${teacherId}`)
                   return result.data.data
                } catch (error) {
                    console.log('error', error)
                }
            }

            async function getListClassroom() {
                try {
                    let result = await axios.get('list-classroom');
                    listClassroom.value = result.data.data
                } catch (error) {
                    console.log('error:', error)
                }
            }
            return {
                message, currentView, showFormCreate, listSubject, getListSubject, listTeacher, listClassroom,
                getListClassroom, getListTeacher, baseColour, refreshListSubject, baseCssColour, dataTeacher,
                handlePassingData, getListBaseTeacherColour, listTeacherSubject, selectTeacherId, handleSelectTeacher,
                hasTeacherBaseColour, checkBaseColour, dataTeacherBy, getTeacherBy
             }
        },
        template: `
            <!-- komponent untuk loading-data-table-subject -->
             <div v-show="currentView === 'loading-table'" class="relative shadow-md sm:rounded-lg">
                <loading-table-subject :visable-card="currentView" />
            </div>

            <!-- komponent untuk data-table-subject -->
             <div v-show="currentView === 'table'" class="relative shadow-md sm:rounded-lg">
               <data-table-subject
                :visable-card="currentView"
                :data="listTeacherSubject"
                @add="handleSelectTeacher"
                />
            </div>

            <!-- komponent untuk form-create-subject -->
             <div v-show="currentView === 'create-subject'" class="relative sm:rounded-lg">
                <form-create-subject
                    :visable-card="currentView"
                    :classrooms="listClassroom"
                    @reload="refreshListSubject"
                    :dataPassingTeacher="dataTeacher"
                    @back-to="currentView = $event"
                />
            </div>

            <!-- komponent untuk form-create-teacher-colour -->
             <div  v-show="currentView === 'create-teacher-colour'"  class="relative sm:rounded-lg">
                <create-teacher-colour
                    :visable-card="currentView"
                    :provide-colour="baseColour"
                    :dataPassingTeacher="dataTeacherBy"
                    @change-to="currentView = $event"
                    @back-to="currentView = $event"
                    @send-back-data="handlePassingData"
                />
            </div>

        `
    }).mount('#app')
}
