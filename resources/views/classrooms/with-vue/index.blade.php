<x-layouts.app :title="__('Kelas')">
    <div id="app" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
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
                {{-- <div v-if="errors.addition_role_id" class="flex items-center p-2 mb-2 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-100 w-full" role="alert">
                    <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                           <a class="hover:underline" href="{{route('roles.index')}}" wire:navigate>Jabatan Tambahan</a> <span class="font-medium font-extrabold dark:text-yellow-600 text-yellow-400">@{{ errors.addition_role_id }}</span> Belum ada..
                    </div>
                </div> --}}

            </div>

            {{-- card-table --}}
            <div
                v-show="currentView === 'table'"
                class="relative shadow-md sm:rounded-lg">
                @include('classrooms.with-vue._card-table-classroom')
            </div>
        </div>
    </div>
     <script type="module">
         const { createApp, ref, reactive, onMounted } = Vue
        createApp({
            setup() {
                const message = ref('Hello Vue!')
                const listClassroom = ref([])
                const classroom = reactive({
                    id: null, name: '', teacher_id: null, major_id: null, teacher: {}, major: {}, students: []
                })
                const currentView = ref("table")
                const showDetailClassroom = ()=> currentView.value = 'detail'
                const disableButton = reactive(false)
                const errors = reactive({ name: '', teacher_id: '', major_id: '' })

                const showFormCreate =()=> currentView.value = 'create'

                onMounted(async ()=> {
                    await getListClassroom()
                });
                async function getListClassroom() {
                    try {
                        const result = await axios.get('/list-classroom');
                        listClassroom.value = result.data.data
                        console.log('data kelas:', listClassroom.value)
                    } catch (error) {
                        console.log(error)
                    }
                }

                function deleteConfirmation(classroomId) {
                    console.log(classroomId)
                }

                function editClassroom(classroomId) {
                    console.log(classroomId)
                }

                async function showClassrrom(classroomId) {
                    try {
                        let result = await axios.get(`classroom-by/${classroomId}`);
                         console.log('data: ', result.data.data)
                    } catch (error) {
                        console.log(error)
                    }
                }
                return {
                    currentView, disableButton, showFormCreate, listClassroom, deleteConfirmation,
                    showClassrrom, classroom, showDetailClassroom,
                }
            }
        }).mount('#app')
    </script>
</x-layouts.app>
