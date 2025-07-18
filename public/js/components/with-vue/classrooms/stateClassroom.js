const { createApp, ref, reactive, onMounted } = Vue
import axios from 'https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/esm/axios.min.js';
import formCreateClassroom from './formCreateClassroom.js';
import loadingTableClassroom from './loadingTableClassroom.js';
import formEditClassroom from './formEditClassroom.js';
import dataTableClassroom from './dataTableClassroom.js';

export default function stateClassroom() {
    createApp({
        components: {
            loadingTableClassroom,
            formCreateClassroom,
            formEditClassroom,
            dataTableClassroom
        },
        setup() {
            const listClassroom = ref([])
            const homeRomeTeacherId = ref(null)
            const isLoading = ref(false)
            const listMajor = ref([])
            const listTeacher = ref([])
            const listStudent = ref([])
            const detailClassroom = reactive({ id: null, name: '', teacher_id: null, major_id: null, teacher: {}, major: {}, students: [] })
            const editClassroom = ref({  id: null, name: '', teacher_id: null, major_id: null, teacher: {}, major: {}, students: [] })
            const currentView = ref("loading-table")
            const disableButton = ref(false)
            const errors = reactive({ name: '', teacher_id: '', major_id: '', student_id: '' })
            const showFormCreate =()=> currentView.value = 'create'

            onMounted(async()=> {
                await getListClassroom()
                await getListMajor()
                await getListTecher()
                await getListStudent()
            });
             async function getListClassroom() {
                try {
                    currentView.value = 'loading-table'
                    const result = await axios.get('/list-classroom');
                    currentView.value = 'table'
                    listClassroom.value = result.data.data
                } catch (error) {
                    console.log(error)
                }
            }

             async function getListMajor() {
                try {
                    let result = await axios.get('/list-major')
                    listMajor.value = result.data.data
                } catch (error) {
                    console.log('error list major', error)
                }
            }

            async function getListTecher() {
                try {
                    let result = await axios.get('/list-teacher')
                    listTeacher.value = result.data.data
                } catch (error) {
                    console.log('error get teacher', error)
                }
            }

            async function getListStudent() {
                try {
                    let result = await axios.get('/list-student')
                    listStudent.value = result.data.data
                    console.log('data students', listStudent.value)
                } catch (error) {
                    if (error.response && error.response.status === 404) {
                        let responseErrors = error.response.data.errors;
                        disableButton.value = true
                        for (let key in responseErrors) {
                            errors[key] = responseErrors[key][0];
                                // generateMessageError(errors[key])
                        }
                    }
                    if (error.response && error.response.status == 409) {
                        disableButton.value = true
                    }else {
                        swalInternalServerError(error.response.data?.message) // http code 500
                    }

                }
            }

            return {
                listClassroom, listMajor, listTeacher, editClassroom, currentView, disableButton,
                listStudent, showFormCreate, homeRomeTeacherId, getListClassroom, getListMajor, getListTecher,
                getListStudent,
            }
        },
        template: `
        <!-- komponent untuk loading-table -->
        <div
            v-show="currentView === 'loading-table'"
            class="relative shadow-md sm:rounded-lg">
            <loading-table-classroom />
        </div>

        <!-- komponent untuk tombol-create-classroom -->
        <div class="flex gap-4">
            <button
                v-show="currentView === 'table'"
                @click="showFormCreate"
                type="button"
                class="text-white mb-2 inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 text-center text-xs dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <svg class="me-1 ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                Kelas
            </button>
            <div
                v-show="listClassroom.length == 0" class="flex items-center p-2 mb-2 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-100 w-full" role="alert">
                <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-medium font-extrabold dark:text-yellow-600 text-yellow-400">Data Kelas belum ada</span>
                </div>
            </div>
        </div>
        <!-- komponent untuk dataTableClassroom -->
        <div
            v-show="currentView === 'table'"
            class="relative shadow-md sm:rounded-lg">
            <data-table-classroom
                :dataProvide="listClassroom"
            />
        </div>

        <!-- komponent untuk form-create-classroom -->

        <!-- komponent untuk form-edit-classroom -->

        <!-- komponent untuk form-create-subject -->

        `
    }).mount('#app')
}
