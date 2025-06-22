<x-layouts.app :title="__('Pengguna')" appName="Example Administrasi">
    <div id="app" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl" wire:ignore>
        @include('partials.settings-heading')

        <div class="relative h-full flex-1 overflow-hidden rounded-xl">
            <div class="flex gap-4">
                {{-- tombol-add user --}}
                <button
                    v-show="currentView === 'table'"
                    @click="showFormCreate"
                    type="button"
                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    user
                </button>
            </div>

            {{-- form create data user general--}}
            <div
                v-cloak
                v-show="currentView === 'create'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    @include('users._card-form-create')
            </div>

            {{-- form create data user detail--}}
            <div
                v-cloak
                v-show="currentView === 'create-user-detail'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <form-user-detail :role-id="roleId" :user-id="userId"/>
            </div>

            {{-- form edit data user general--}}
            <div
                v-cloak
                v-show="currentView === 'edit'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    @include('users._card-form-edit')
            </div>

            {{-- card show data user detail --}}
            <div
                v-cloak
                v-show="currentView === 'detail'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                @include('users._card-detail-user')
            </div>

            {{-- card table data user --}}
            <div
                v-show="currentView === 'table'"
                class="relative shadow-md sm:rounded-lg">
                @include('users._card-table-user')
            </div>
            <div
              v-show="currentView === 'table'"
            >
            <pagination :data-user="links" @paginate="getListUser" />
            </div>
        </div>
        {{-- pagination --}}


    </div>

    <script type="module">
          const { createApp, ref, reactive, onMounted, watch } = Vue
        // import { createApp, ref, reactive, onMounted, watch } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js'
        import axios from 'https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/esm/axios.min.js';
        import pagination from '/js/components/paginate.js';
        import formUserDetail from '/js/components/formUserDetail.js';
        createApp({
            components: {
                pagination,
                formUserDetail,
            },
            setup() {
                const user = ref({
                    userId: '',
                    roleId: '',
                    name: '',
                    address: ''
                });
                const users = ref([]);
                const search = ref("")
                const currentView = ref('table')
                const perPage = ref(1);
                const listRole = ref([{id: 1, name: 'kepala-sekolah'},{id: 2, name: 'admin'},{id: 3, name: 'guru'}, {id: 4, name: 'siswa'}, {id: 5, name: 'orang-tua'}])
                const dataUserGeneral = reactive({ name: '',  email: '',  password: '',  role_id: '' });
                const errors = reactive({ name: '', email: '', password: '', role_id: '', message: '' })
                const links = ref([])
                const roleId = ref(0);
                const userId = ref(0)
                const showDetailUser = async(id) => {
                    try {
                        const result = await axios.get('user/' + id);
                        user.value = result.data.data
                        currentView.value = 'detail'
                    } catch (error) {
                        console.log(error)
                    }
                }
                const showFormCreate = () => currentView.value = 'create'
                const showFormEdit = () => currentView.value = 'edit'
                const showTable = () =>  currentView.value = 'table'
                const closeCreateForm = () => {
                    let isAnyFilled = Object.values(dataUserGeneral).some( value => value !== '')
                    if (isAnyFilled) {
                        Swal.fire({
                            title: 'yakin membatalkan?',
                            text: 'Data ini akan dihapus dan tidak bisa dikembalikan!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, batalkan!',
                            cancelButtonText: 'Batal'
                        }).then((result)=> {
                            if (result.isConfirmed) {
                                currentView.value = 'table';
                                resetField();
                                resetError()
                            }
                        })
                    } else {
                        currentView.value = 'table'
                        resetError()
                    }

                }
                const page = ref(1)
                const editUser = async (id) => {
                     try {
                        const result = await axios.get('user/' + id);
                        user.value = result.data.data
                        currentView.value = 'edit'
                    } catch (error) {
                        console.log(error)
                    }
                 }

                function deleteConfirm(id) {
                    console.log(id)
                }

                const searchUser = async () => {
                    try {
                        let result = await axios.get("/search-user/?search=" + search.value)
                        users.value = result.data.data.data
                    } catch (error) {
                        console.log(error.response)
                        if (error.response.status == 404) {
                               errors.message = error.response.data.message
                        }else {
                            errors.message = error.response.message
                        }
                    }
                }

                async function storeDataUserGeneral() {
                    try {
                        if (!dataUserGeneral.name.trim()) {
                           errors.name = 'nama tidak boleh kosong'
                        }
                        if (!dataUserGeneral.email.trim()) {
                           errors.email = 'email tidak boleh kosong'
                        }

                        if (!dataUserGeneral.email.trim()) {
                           errors.password = 'email tidak boleh kosong'
                        }

                        if (!dataUserGeneral.role_id) {
                           errors.role_id = 'jabatan tidak boleh kosong'
                        }

                        let sendData = {
                            'name': dataUserGeneral.name,
                            'email': dataUserGeneral.email,
                            'password': dataUserGeneral.password,
                            'role_id': dataUserGeneral.role_id
                        }


                        let result = await axios.post('/store-data-user-general', sendData);
                        // userId.value = result.data.data.user_id;
                        // roleId.value = result.data.data.role_id
                        // currentView.value = 'create-user-detail';
                        currentView.value = 'table';
                    } catch (error) {
                        console.log(error)
                    }
                }

                function resetField() {
                    Object.assign(dataUserGeneral, {
                        name: '',
                        email: '',
                        password: '',
                        role_id: ''
                    });
                }
                function resetError() {
                    Object.assign(errors, {
                        name: '',
                        email: '',
                        password: '',
                        role_id: ''
                    });
                }
                const getListUser = async (dataUser = `list-user?page=${page.value}`) => {
                    try {
                        const result = await axios.get(dataUser)
                       users.value = result.data.data?.data || []
                       page.value = result.data.data
                       links.value = result.data.data
                    } catch (error) {

                        console.log(error)
                    }
                }

                watch( search, async (newSearch) => {
                    newSearch ?  await searchUser() :  await getListUser();
                 })

                onMounted( async ()=> {
                    await getListUser()
                })
                return {
                    deleteConfirm, editUser,listRole,users, getListUser,
                    links, search,searchUser, perPage, page,errors, roleId,
                    userId,
                    storeDataUserGeneral, dataUserGeneral, user, currentView,
                    showFormCreate, showFormEdit, showDetailUser, showTable,
                    closeCreateForm,
                }
            }
        }).mount('#app')
    </script>
</x-layouts.app>
