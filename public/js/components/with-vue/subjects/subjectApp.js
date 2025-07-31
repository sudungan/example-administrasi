    import dataTableSubject from "./dataTableSubject.js"
    import formCreateSubject from "./formCreateSubject.js"
    import loadingTableSubject from "./loadingTableSubject.js"
    import createTeacherColour from "./createTeacherColour.js"
    import cardShowListSubject from "./cardShowListSubject.js"
    const { createApp, ref, reactive, onMounted } = Vue
export default function subjectApp () {

    createApp({
        components: { dataTableSubject, formCreateSubject, loadingTableSubject, createTeacherColour, cardShowListSubject },
        setup() {
            const currentView = ref("loading-table")
            const message = ref('Hello Vue!')
            const listTeacherSubject = ref([])
            const hasTeacherBaseColour = ref(null)
            const dataTeacherBy = ref({ id: '', name: '' })
            const showListSubjectsTeacher = ref([])
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

            onMounted(async ()=> {
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
                await getListTeacherSubject()
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
                    dataTeacherBy.value = await getTeacherBy(data.teacherId)
                    hasTeacherBaseColour.value =  await checkBaseColour(data.teacherId)
                    if (hasTeacherBaseColour.value) {
                        currentView.value = data.component   // 'create-subject'
                    }else {
                        currentView.value = 'create-teacher-colour'
                    }
                } catch (error) {
                    console.log('error', error)
                }
            }

            async function handleShowSubjectTeacher(data) {
                try {
                    let result = await axios.get(`/list-subject-by/${data.teacherId}`)
                    showListSubjectsTeacher.value = result.data.data
                    console.log('data', showListSubjectsTeacher.value)
                    currentView.value = 'show-list-subject-by'
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
                    let result = await axios.get(`/teacher-by/${teacherId}`)
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
                getListClassroom, getListTeacher, baseColour, refreshListSubject, baseCssColour, handleShowSubjectTeacher,
                dataTeacher, handlePassingData, listTeacherSubject, handleSelectTeacher,showListSubjectsTeacher,
                hasTeacherBaseColour, checkBaseColour, dataTeacherBy, getTeacherBy,
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
                @show="handleShowSubjectTeacher"
                />
            </div>

            <!-- komponent untuk show-list-subject-by-teacherId -->
             <div v-show="currentView === 'show-list-subject-by'" class="relative shadow-md sm:rounded-lg">
               <card-show-list-subject
                :visable-card="currentView"
                :data="showListSubjectsTeacher"
                @back-to="currentView = $event"
                />
            </div>

            <!-- komponent untuk form-create-subject -->
             <div v-show="currentView === 'create-subject'" class="relative sm:rounded-lg">
                <form-create-subject
                    :visable-card="currentView"
                    :classrooms="listClassroom"
                    @reload="refreshListSubject"
                    :dataPassingTeacher="hasTeacherBaseColour"
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
