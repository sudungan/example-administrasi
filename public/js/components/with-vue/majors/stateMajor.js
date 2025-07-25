const { createApp, ref, reactive, onMounted } = Vue
import axios from 'https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/esm/axios.min.js';
import dataTableMajor from './dataTableMajor.js';
import loadingTableMajor from './loadingTableMajor.js';
export default function stateMajor() {
    createApp({
        components: {
            dataTableMajor,
            loadingTableMajor
        },
        setup() {
            const listMajor = ref([])
            const headMajorById = ref(null)
            const disableButton = ref(false)
            const errorHeadMajor = ref("")
            const errors = reactive({ name: '', user_id: '',  addition_role_id: '' })
            const listTeacher = ref([])
            const currentView = ref("loading-table")
            const isLoading = ref(false)

            const showFormCreate =()=> currentView.value = 'create'

            const getListMajor = async ()=> {
                try {
                    currentView.value = 'loading-table'
                    const result = await axios.get('/list-major');
                    currentView.value = 'table'
                    listMajor.value = result.data.data;
                } catch (error) {
                    console.log(error)
                }
            }

            const getListTeacher = async()=> {
                try {
                    let result = await axios.get('/list-teacher')
                    listTeacher.value = result.data.data
                } catch (error) {
                    if (error.response && error.response.status === 404) {
                        let responseErrors = error.response.data.errors;
                        disableButton.value = true
                        for (let key in responseErrors) {
                            errors[key] = responseErrors[key][0];
                                generateMessageError(errors[key])
                        }
                    }else {
                        console.log(error)
                        // swalInternalServerError(error.response.message)
                    }
                }
            }

            const getHeadMajorById = async()=> {
                    try {
                    let result = await axios.get('/head-major-byId')
                    headMajorById.value = result.data.data

                } catch (error) {
                    if (error.response && error.response.status === 404) {
                        let responseErrors = error.response.data.errors;
                        disableButton.value = true
                        for (let key in responseErrors) {
                            errors[key] = responseErrors[key][0];
                            console.log('testing', errors[key])
                                generateMessageError(errors[key])
                        }
                    }
                    if (error.response && error.response.status === 422) {
                        let responseErrors = error.response.data.errors;
                        for (let key in responseErrors) {
                            errors[key] = responseErrors[key][0];
                                console.log('testing', errors[key])
                        }
                        isLoading.value = false
                    }else {
                        console.log(error)
                        // swalInternalServerError(error.response.data.message) // http code 500
                    }
                }
            }

            const generateMessageError = (text)=> {
                let word = text.split(" ")
                errors.addition_role_id = word.slice(1, -2).join(" ");
            }

            onMounted(async ()=> {
                await getListMajor()
                await getListTeacher()
                await getHeadMajorById()
            });
            return {
                listMajor, disableButton, listTeacher, currentView, isLoading, getListMajor,
                getListTeacher, getHeadMajorById, generateMessageError, errors, showFormCreate,

            }
        },
        template: `
            <div class="relative h-full flex-1 overflow-hidden rounded-xl">

                <div class="flex gap-4">
                    <!-- komponent untuk btnFormCreateMajor -->
                    <button
                        v-show="currentView === 'table'"
                        @click="showFormCreate"
                        type="button"
                        :disabled="disableButton"
                        :class="{ 'opacity-50 cursor-not-allowed': disableButton }"
                        class="text-white mb-2 inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 text-center text-xs dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-1 ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        Jurusan
                    </button>
                    <div v-if="errors.addition_role_id" class="flex items-center p-2 mb-2 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-100 w-full" role="alert">
                        <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="sr-only">Info</span>
                        <div>
                            <a class="hover:underline" href="{{route('roles.index')}}" wire:navigate>Jabatan Tambahan</a> <span class="font-medium font-extrabold dark:text-yellow-600 text-yellow-400">{{ errors.addition_role_id }}</span> Belum ada..
                        </div>
                    </div>

                    <div v-if="errors.teacher_id" class="flex items-center p-2 mb-2 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-100 w-full" role="alert">
                        <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="sr-only">Info</span>
                        <div>
                            <a class="hover:underline" href="{{route('users.index')}}" wire:navigate>Check Kembali, </a> <span class="font-medium font-extrabold dark:text-yellow-600 text-yellow-400">{{ errors.teacher_id }}</span>
                        </div>
                    </div>

                    <div v-if="listMajor.length == 0" class="flex items-center p-2 mb-2 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-100 w-full" role="alert">
                        <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="sr-only">Info</span>
                        <div>
                            <span class="font-medium font-extrabold dark:text-yellow-600 text-yellow-400">Jurusan belum ada</span>
                        </div>
                    </div>
                </div>


            <!-- komponent untuk data-table-major -->
            <div
                v-show="currentView === 'table'"
                class="relative shadow-md sm:rounded-lg">
                <data-table-major :dataProvide="listMajor" />
            </div>

            <!-- komponent untuk loading-data-table-major -->
            <div
                v-show="currentView === 'loading-table'"
                class="relative shadow-md sm:rounded-lg">
                <loading-table-major />
            </div>

            <!-- komponent untuk form-create-major -->
            <div
                v-cloak
                v-show="currentView === 'create'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">

            </div>

            <!-- komponent untuk form-edit-major -->
            <div
                v-cloak
                v-show="currentView === 'edit'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">

            </div>
        </div>






        `
    }).mount("#app")
}
