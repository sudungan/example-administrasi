<x-layouts.app :title="__('Kelas')">
    <div id="app"  wire:ignore  class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
         @include('partials.classrooms-heading')
         <div class="relative h-full flex-1 overflow-hidden rounded-xl">
            {{-- button add --}}
            <div class="flex gap-4">
                <button
                    v-show="currentView === 'table'"
                    @click="showFormCreate"
                    type="button"
                    :disabled="disableButton"
                    :class="{ 'opacity-50 cursor-not-allowed': disableButton }"
                    class="text-white mb-2 inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 text-center text-xs dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Kelas
                </button>
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

            {{-- card-loading-table --}}
            <div
                v-show="currentView === 'loading-table'"
                class="relative shadow-md sm:rounded-lg">
                @include('classrooms.with-vue._card-loading-table')
            </div>

            {{-- card-form-create --}}
            <div
                v-cloak
                v-show="currentView === 'create'"
                class="relative sm:rounded-lg">
                <form-create-classroom :visable-card="currentView" :majors="listMajor" :teachers="listTeacher" :students="listStudent"  @back-to="currentView = $event" @reload="handleReloadClassroom" />
                {{-- </form-create-classroom> --}}
            </div>
        </div>
    </div>
     <script type="module">
        import axios from 'https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/esm/axios.min.js';
        const { createApp, ref, reactive, onMounted } = Vue
        import formCreateClassroom from '/js/components/formCreateClassroom.js';
        createApp({
            components: {
                formCreateClassroom
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
                const currentView = ref("loading-table")
                const showDetailClassroom = ()=> currentView.value = 'detail'
                const disableButton = ref(false)
                const errors = reactive({ name: '', teacher_id: '', major_id: '' })

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

                async function handleReloadClassroom() {
                    await getListClassroom() // ambil data terbaru
                }

                function deleteConfirmation(classroomId) {
                    console.log(classroomId)
                }

                function editClassroom(classroomId) {
                    console.log(classroomId)
                }

                function searchClassroom() {

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

                  const generateMessageError = (text)=> {
                    let word = text.split(" ")
                    errors.addition_role_id = word.slice(1, -2).join(" ");
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
                        console.log('Parent - listMajor:', listMajor.value)
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
                        console.log('error list student:', error)
                    }
                }
                return {
                    currentView, disableButton, showFormCreate, listClassroom, deleteConfirmation, isLoading,
                    showClassrrom, detailClassroom, search, searchClassroom, editClassroom, listTeacher,
                    closeCreateForm, homeRomeTeacherId,handleReloadClassroom, listMajor, getListMajor,
                    listStudent, getListStudent,
                }
            }
        }).mount('#app')
    </script>
</x-layouts.app>
