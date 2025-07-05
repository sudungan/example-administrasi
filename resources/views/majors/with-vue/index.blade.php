<x-layouts.app :title="__('Jurusan')" appName="Example Administrasi">
    <div id="app" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl" wire:ignore>
        @include('partials.majors-heading')


         <div class="relative h-full flex-1 overflow-hidden rounded-xl">
            <div class="flex gap-4">
                <button
                    v-show="currentView === 'table'"
                    @click="showFormCreate"
                    type="button"
                    class="text-white mb-2 inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Jurusan
                </button>
            </div>
            <div
                v-show="currentView === 'table'"
                class="relative shadow-md sm:rounded-lg">
                @include('majors.with-vue._card-table-major')
            </div>

            <div
                v-cloak
                v-show="currentView === 'create'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                @include('majors.with-vue._card-form-create')
            </div>

            {{-- <div
                v-cloak
                v-show="currentView === 'edit'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    @include('majors.with-vue._card-form-edit')
            </div> --}}

        </div>
    </div>
    <script type="module">
        const { createApp, ref, onMounted, reactive } = Vue
                //   const { createApp, ref, toRefs, reactive, onMounted, watch } = Vue
        createApp({
            setup() {
                const message = ref('Hello vue!')
                const listMajor = ref([])
                const listTeacher = ref([])
                const currentView = ref("table")
                const isLoading = ref(false)
                const errors = ref({ name: '', teacher_id: '' })
                const fields = {name: 'Nama', teacher_id: 'Kepala Jurusan'}
                const major = reactive({ id: '', name: '', teacher_id: '' })
                const showFormCreate =()=> currentView.value = 'create'
                const closeCreateForm =()=> currentView.value = 'table'
                const getListMajor = async ()=> {
                    try {
                        const result = await axios.get('/list-major');
                        listMajor.value = result.data.data;
                    } catch (error) {
                        console.log(error)
                    }
                }

                const getListTeacher = async()=> {
                    try {
                        let result = await axios.get('/list-get-teacher')
                        listTeacher.value = result.data.data
                    } catch (error) {
                        console.log(error)
                    }
                }

                const editMajor =(majorId)=> {

                }
                const deleteConfirmation =(majorId)=> {

                }

                const storeMajor =()=> {

                }
                onMounted(async ()=> {
                    await getListMajor()
                    await getListTeacher()
                });
                return {
                    currentView, listMajor, isLoading, errors, fields, major, message,
                    showFormCreate, closeCreateForm, editMajor, deleteConfirmation, storeMajor,
                    listTeacher,
                }
            }
        }).mount('#app')
        </script>
</x-layouts.app>
