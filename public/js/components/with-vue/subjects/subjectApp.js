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
                handlePassingData, getListBaseTeacherColour,
             }
        },
        template: `
            <div class="flex gap-2">
            <!-- todo-list: bila users: teacher tidak ada akan disable -->
                <button
                    v-show="currentView === 'table'"
                    @click="showFormCreate"
                    type="button"
                    class="text-white mb-2 inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 text-center text-xs dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Mata Pelajaran
                </button>
            </div>

            <!-- komponent untuk loading-data-table-subject -->
             <div v-show="currentView === 'loading-table'" class="relative shadow-md sm:rounded-lg">
                <loading-table-subject :visable-card="currentView" />
            </div>

            <!-- komponent untuk data-table-subject -->
             <div v-show="currentView === 'table'" class="relative shadow-md sm:rounded-lg">
               <data-table-subject :visable-card="currentView" :data="listSubject" />
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
                    :teachers="listTeacher"
                    :provide-colour="baseColour"
                    :dataPassingTeacher="dataTeacher"
                    @change-to="currentView = $event"
                    @send-back-data="handlePassingData"
                />
            </div>

        `
    }).mount('#app')
}
