<x-layouts.app :title="__('Kelas')">
    <div wire:ignore class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
         @include('partials.classrooms-heading')
         <div id="app" class="relative h-full flex-1 overflow-hidden rounded-xl">
            {{-- button add --}}
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

            {{-- card-table-classroom --}}
            <div
                v-show="currentView === 'table'"
                class="relative shadow-md sm:rounded-lg">
                @include('classrooms.with-vue._card-table-classroom')
            </div>

            {{-- card-detail-classroom --}}
            <div
                v-show="currentView === 'detail'"
                class="relative shadow-md sm:rounded-lg">
                @include('classrooms.with-vue._card-detail-classroom')
            </div>

            {{-- component-loading-table --}}
            <div
                v-show="currentView === 'loading-table'"
                class="relative shadow-md sm:rounded-lg">
                <loading-table-classroom />
                {{-- @include('classrooms.with-vue._card-loading-table') --}}

            </div>

            {{-- component-form-create --}}
            <div
                v-cloak
                v-show="currentView === 'create'"
                class="relative sm:rounded-lg">
                    <form-create-classroom
                        :visable-card="currentView"
                        :majors="listMajor"
                        :teachers="listTeacher"
                        :waiting-process="isLoading"
                        :students="listStudent"
                        :classroom="editClassroom"
                        @back-to="currentView = $event"
                        @reload="refreshListClassrooom"
                    />
            </div>

            {{-- component-form-edit --}}
            <div
                v-cloak
                v-show="currentView === 'edit'"
                class="relative sm:rounded-lg">
                    <form-edit-classroom
                        :visable-card="currentView"
                        :majors="listMajor"
                        :teachers="listTeacher"
                        :students="listStudent"
                        @back-to="currentView = $event"
                        @reload="refreshListClassrooom"
                    />
            </div>

        </div>
    </div>
     <script type="module">
        import axios from 'https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/esm/axios.min.js';
        const { createApp, ref, reactive, onMounted } = Vue
        import formCreateClassroom from '/js/components/with-vue/classrooms/formCreateClassroom.js';
        import loadingTableClassroom from '/js/components/with-vue/classrooms/loadingTableClassroom.js';
        import formEditClassroom from '/js/components/with-vue/classrooms/formEditClassroom.js';
        createApp({
            components: {
                formCreateClassroom,
                loadingTableClassroom,
                formEditClassroom
            },
            setup() {
                const message = ref('Hello Vue!')
                const listClassroom = ref([])
                const homeRomeTeacherId = ref(null)
                const search = reactive("")
                const isLoading = ref(false)
                const listMajor = ref([])
                const listTeacher = ref([])
                const listStudent = ref([])
                const detailClassroom = reactive({
                    id: null, name: '', teacher_id: null, major_id: null, teacher: {}, major: {}, students: []
                })
                const editClassroom = ref({
                    id: null, name: '', teacher_id: null, major_id: null, teacher: {}, major: {}, students: []
                })
                const currentView = ref("loading-table")
                const showDetailClassroom = ()=> currentView.value = 'detail'
                const disableButton = ref(false)
                const errors = reactive({ name: '', teacher_id: '', major_id: '', student_id: '' })

                const showFormCreate =()=> currentView.value = 'create'
                const closeCreateForm =()=> {
                    currentView.value = 'table'
                }
                onMounted(async ()=> {
                    await getListClassroom()
                    await getHomeRomeTeacherId()
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

                function deleteConfirmation(classroomId) {
                    console.log(classroomId)
                }

                async function btnEditClassroom(classroomId) {
                    try {
                        let result = await axios.get(`/edit-classroom-by/${classroomId}`)
                        editClassroom.value = result.data.data
                        currentView.value = 'edit'
                    } catch (error) {
                        console.log(error)
                    }
                }

                function searchClassroom() {

                }

                async function refreshListClassrooom() {
                    await getListClassroom()
                }

                async function showClassrrom(classroomId) {
                    try {
                        let result = await axios.get(`classroom-by/${classroomId}`);
                        currentView.value = 'detail'
                        let dataClassroom = result.data.data

                        detailClassroom.id= dataClassroom.id
                        detailClassroom.name = dataClassroom.name
                        detailClassroom.teacher_id = dataClassroom.teacher_id
                        detailClassroom.major_id = dataClassroom.major_id
                        detailClassroom.teacher = dataClassroom.teacher
                        detailClassroom.major = dataClassroom.major
                        detailClassroom.students = dataClassroom.students
                        console.log('data kelas: ', dataClassroom.students)
                    } catch (error) {
                        console.log('error:',error)
                    }
                }

                const generateMessageError = (message)=> {
                    errors.student_id = message;
                }

                async function getHomeRomeTeacherId() {
                    try {
                        let result = await axios.get('/home-rome-teacher-id');
                        homeRomeTeacherId.value = result.data.data;
                    } catch (error) {
                        if (error.response && error.response.status === 404) {
                            let responseErrors = error.response.data.errors;
                            disableButton.value = true
                            for (let key in responseErrors) {
                                errors[key] = responseErrors[key][0];
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
                                  generateMessageError(errors[key])
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
                    currentView, disableButton, showFormCreate, listClassroom, deleteConfirmation, isLoading,
                    showClassrrom, detailClassroom, search, searchClassroom, btnEditClassroom, listTeacher,
                    closeCreateForm, homeRomeTeacherId, listMajor, getListMajor,
                    listStudent, getListStudent, errors, editClassroom, refreshListClassrooom,
                }
            }
        }).mount('#app')
    </script>
</x-layouts.app>
