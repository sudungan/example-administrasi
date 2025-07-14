    import dataTableSubject from "./dataTableSubject.js"
    import formCreateSubject from "./formCreateSubject.js"
    import loadingTableSubject from "./loadingTableSubject.js"
    const { createApp, ref, reactive, onMounted } = Vue
export default function subjectApp () {

    createApp({
        components: {
            dataTableSubject,
            formCreateSubject,
            loadingTableSubject
        },
        setup() {
            const currentView = ref("loading-table")
            const message = ref('Hello Vue!')
            const listSubject = ref([])
            const listTeacher = ref([])
            const listClassroom = ref([])
            const showFormCreate =()=> currentView.value = 'create'

            onMounted(async ()=>{
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
                    console.log('data dari list-subject', listSubject.value)
                } catch (error) {
                    console.log('error:', error)
                }
            }

            async function getListTeacher() {
                try {
                    let result = await axios.get('list-teacher');
                    listTeacher.value = result.data.data
                    console.log('data dari list-teacher', listTeacher.value)
                } catch (error) {
                    console.log('error:', error)
                }
            }

            async function getListClassroom() {
                try {
                    let result = await axios.get('list-classroom');
                    listClassroom.value = result.data.data
                    console.log('data dari list-classroom', listClassroom.value)
                } catch (error) {
                    console.log('error:', error)
                }
            }
            return {
                message, currentView, showFormCreate, listSubject, getListSubject, listTeacher, listClassroom,
                getListClassroom, getListTeacher
             }
        },
        template: `
            <div class="flex gap-2">
                <button
                    v-show="currentView === 'table'"
                    @click="showFormCreate"
                    type="button"
                    class="px-3 py-2 flex w-auto text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Mata Pelajaran
                </button>
                <div v-if="listSubject.length == 0 && currentView === 'table'" class="flex items-center p-2 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
                    <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                        <span class="font-medium">Data Mata Pelajaran Belum ada...</span>
                    </div>
                </div>
            </div>

            <!-- komponent untuk loading-data-table-subject -->
             <div v-show="currentView === 'loading-table'" class="relative shadow-md sm:rounded-lg">
                <loading-table-subject :visable-card="currentView" />
            </div>

            <!-- komponent untuk data-table-subject -->
             <div
                v-show="currentView === 'table'"
                class="relative shadow-md sm:rounded-lg">
               <data-table-subject
                    :visable-card="currentView"
                    :data="listSubject"
                    @backTo="currentView = $event"
                />
            </div>

            <!-- komponent untuk form-create-subject -->
             <div
                v-show="currentView === 'create'"
                class="relative shadow-md sm:rounded-lg">

               <form-create-subject
                    :visable-card="currentView"
                    @back-to="currentView = $event"
               />
            </div>
        `
    }).mount('#app')
}
