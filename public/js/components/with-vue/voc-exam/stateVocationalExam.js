    const { createApp, ref, reactive, onMounted, nextTick  } = Vue
    import loadingTableVocationalExam from "./loadingTableVocationalExam.js"
    import dataTableVocationalExam from "./dataTableVocationalExam.js"
    import formCreateVocationalExam from "./formCreateVocationalExam.js"
    import formEditVocationalExam from "./formEditVocationalExam.js"

export default function stateVocationalExam() {
    createApp({
        components: {
            loadingTableVocationalExam, dataTableVocationalExam, 
            formCreateVocationalExam, formEditVocationalExam
        },
        setup() {
            const currentView = ref("loading-table")
            const listVocationExam = ref([])
            const editExam = ref({ id: '', name: '', period: '', description: '' })
            const showFormCreate = () => currentView.value = 'create'
            const showTable = () =>  currentView.value = 'table'
            const isLoading = ref(false)

            onMounted( async()=> {
                await getListVocationExam()
                await refreshListVocationalExam()
            })

            async function getListVocationExam() {
                try {
                    const result = await axios.get('list-vocational-exam')
                    listVocationExam.value = result.data.data
                    currentView.value = 'table'
                } catch (error) {
                    console.log('error', error)
                }
            }

            const refreshListVocationalExam = async ()=> {
                await getListVocationExam()
            }

            const getEditExam = async(examId)=> {
                try {
                    const result = await axios.get(`edit-exam-by/${examId}`)
                    editExam.value = result.data.data
                    console.log('data', editExam.value)
                    currentView.value = 'edit-exam-component'
                } catch (error) {
                    
                }
            }
            return {
                showFormCreate, currentView, showTable, listVocationExam, refreshListVocationalExam,
                isLoading, getEditExam, editExam
            }
        },
        template: `
            <!-- komponent untuk tombol-create-classroom -->
            <div class="flex gap-4">
                <button
                    v-show="currentView === 'table'"
                    @click="showFormCreate"
                    type="button"
                    class="text-white mb-2 inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 text-center text-xs dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Ujian Keahlian
                </button>
            </div>

            <!-- komponent untuk loading-data-table-subject -->
             <div v-show="currentView === 'loading-table'" class="relative shadow-md sm:rounded-lg">
             <loading-table-vocational-exam :visable-card="currentView" />
            </div>

            <!-- komponent untuk data-table-vocational-exam -->
            <div v-cloak v-show="currentView === 'table'" class="relative shadow-md sm:rounded-lg">
                <data-table-vocational-exam
                    :visable-card="currentView"
                    @reload="refreshListVocationalExam"
                    @edit="getEditExam"
                    :data-provide-by="listVocationExam"
                />
            </div>

            <!-- komponent untuk show-list-subject-by-teacherId -->


            <!-- komponent untuk form-edit-ujian-keahlian -->
            <div
                v-cloak
                v-show="currentView === 'edit-exam-component'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <form-edit-vocational-exam
                    :exam="editExam"
                    :visable-card="currentView"
                    :waiting-process="isLoading"
                    @reload="refreshListVocationalExam"
                    @back-to="currentView = $event"
                />
            </div>

            <!-- komponent untuk form-add-create-vocational-exam -->
             <div
                v-cloak
                v-show="currentView === 'create'" class="relative sm:rounded-lg">
                    <form-create-vocational-exam 
                        :visable-card="currentView"
                        :waiting-process="isLoading"
                        @reload="refreshListVocationalExam"
                        @back-to="currentView = $event"
                    />
            </div>
            <!-- komponent untuk form-create-teacher-colour -->


        `
    }).mount("#app");
}
