<x-layouts.app :title="__('Pengguna')" appName="Example Administrasi">
    <div id="app" wire:ignore class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
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
                    @include('users.with-vue._card-form-create')
            </div>

              {{-- card-loading-table --}}
            <div
                v-show="currentView === 'loading-table'"
                class="relative shadow-md sm:rounded-lg">
               <loading-table-user />
            </div>

            {{-- form create data user detail--}}
            <div
                v-cloak
                v-show="currentView === 'create-user-detail'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <form-user-detail
                    :role-id="roleId"
                    :user-id="userId"/>
            </div>

            {{-- form edit data user general--}}
            <div
                v-cloak
                v-show="currentView === 'edit'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <form-edit-user
                        :visable-card="currentView"
                        :user-to="editData"
                        :provide-role-from="listRole"
                        :waiting-process="isLoading"
                        :displays="errors"
                        @back-to="currentView = $event"
                        @reload="toRefreshListUser"
                    />
            </div>

            {{-- card show data user detail --}}
            <div
                v-cloak
                v-show="currentView === 'detail'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                @include('users.with-vue._card-detail-user')
                {{-- @include('users.with-vue._card-detail-user-example') --}}
            </div>

            {{-- card table data user --}}
            <div
                v-show="currentView === 'table'"
                class="relative shadow-md sm:rounded-lg">
                @include('users.with-vue._card-table-user')
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
        const { createApp, ref, toRefs, reactive, onMounted, watch } = Vue
        import pagination from '/js/components/paginate.js';
        import formUserDetail from '/js/components/formUserDetail.js';
        import inputPassword from '/js/components/inputPassword.js';
        import formEditUser from '/js/components/with-vue/users/formEditUser.js'
        import loadingTableUser from '/js/components/with-vue/users/loadingTableUser.js';
        createApp({
            components: {
                pagination,
                formUserDetail,
                inputPassword,
                formEditUser,
                loadingTableUser
            },
            setup() {
                const users = ref([]);
                const search = ref("")
                const links = ref([])
                const listRole = ref([])
                const roleId = ref(0);
                const userId = ref(0)
                const perPage = ref(1);
                const currentView = ref('table')
                const isLoading = ref(false)
                const cardUserDetail = reactive({ general:false,  profile: false })
                const user = ref({ userId: '', roleId: '',  name: '', address: ''  });
                const editData = ref({ id: '', name: '',  email: '',  password: '',  role_id: '', classroom_id: ''  })
                const dataUserGeneral = reactive({ name: '',  email: '',  password: '',  role_id: '' });
                const dataUserProfile = ref({
                    first_name: '', last_name: '', address: '', phone_number: '',
                    status: '', place_of_birth: '', date_of_birth: '', nis: '',
                    user: {},
                    classroom: {},
                    major: {}
                })
                const errors = reactive({ name: '', email: '', password: '', role_id: '', message: '' })
                const fieldLabels = { name: 'Nama', email: 'Email', password: 'Password',  role_id: 'Jabatan' };
                const { name, email, password, role_id } = toRefs(dataUserGeneral);

                const showDetailProfile = async(userId) => {
                    try {
                        let result = await axios.get(`/user-profile-by/${userId}`)
                        dataUserProfile.value = result.data.data;
                        console.log('data-user-profile', result)
                    } catch (error) {
                        // console.log('error-data-user-profile', error)
                    }
                }
                const showDetailUser = async(userId) => {
                    try {
                        const result = await axios.get(`/show-user-by/${userId}`);
                        user.value = result.data.data
                        currentView.value = 'detail'
                        await showDetailProfile(userId)
                    } catch (error) {
                        console.log(error)
                    }
                }
                const backToPreviousPage = () => {
                    currentView.value = 'table'
                    cardUserDetail.general = false
                    cardUserDetail.profile = false

                }
                const btnShowDetailProfile =()=> {
                    cardUserDetail.profile = ! cardUserDetail.profile
                    cardUserDetail.general = false
                }
                const btnShowDataGeneral =()=> {
                    cardUserDetail.general = !cardUserDetail.general
                    cardUserDetail.profile = false
                }
                const showFormCreate = () => currentView.value = 'create'
                const showFormEdit = () => currentView.value = 'edit'
                const showTable = () =>  currentView.value = 'table'
                const closeCreateForm = () => {
                    let isAnyFilled = Object.values(dataUserGeneral).some( value => value !== '') // mengambil sebagian alue dataUserGenaral
                    if (isAnyFilled) {
                        // menggunakan cancelConfirmation dari public js/helper
                        cancelConfirmation('Yakin membatalkan?', (result)=> {
                            if (result.isConfirmed) {
                                currentView.value = 'table';
                                resetField();
                                resetError()
                            }
                        });
                    }else {
                        currentView.value = 'table'
                        resetError()
                    }
                }
                const page = ref(1)
                async function btnEditUser(userId) {
                     try {
                        let result = await axios.get(`/edit-user-by/${userId}`);
                        editData.value = result.data.data
                        currentView.value = 'edit'
                    } catch (error) {
                        console.log(error)
                    }
                 }

                function deleteConfirm(userId) {
                     confirmDelete('Yakin dihapus?', async (result)=>{
                        if(!result.isConfirmed) {
                            return
                        }
                        await swalLoading('Menghapus Data Jurusan..',async (result)=> {
                            try {
                                let result = await axios.delete(`/delete-user/${userId}`)
                                successNotification(result.data.message)
                                getListUser()
                            } catch (error) {
                                if (error.response && error.response.status == 409) {
                                    currentView.value = 'table'
                                    swalNotificationWarning(error.response.data.message)
                                }else {
                                    swalInternalServerError(error.response.data.message) // http code 500
                                }
                            }
                        });
                    })
                }

                const searchUser = async () => {
                    try {
                        let result = await axios.get("/search-user/?search=" + search.value) // endpoint
                        users.value = result.data.data.data // mengambil data dari response api dan dikoleksi
                    } catch (error) { // menangkap objek error
                        console.log(error.response)
                        if (error.response.status == 404) { // validasi error response status yang dikirim dari back-end
                               errors.message = error.response.data.message // mengambil pesan error dan menampilkan
                        }else {
                            errors.message = error.response.message
                        }
                    }
                }

                async function storeDataUserGeneral() {
                    try {
                        let isValid = true;
                        for (let key in dataUserGeneral) { // validasi inputan data dibongkar melalui objek dataUserGeneral
                            if (!dataUserGeneral[key].toString().trim()) {
                                let label = fieldLabels[key] || key;
                                errors[key] = `${label} tidak boleh kosong`;
                                isValid = false;
                            } else {
                                errors[key] = '';
                            }
                        }

                        if (!isValid) return

                        let sendData = { // mengkoleksi data user kedalam objek
                            'name': dataUserGeneral.name,
                            'email': dataUserGeneral.email,
                            'password': dataUserGeneral.password,
                            'role_id': dataUserGeneral.role_id
                        }
                        isLoading.value = true;
                        let result = await axios.post('/store-data-user-general', sendData);
                        currentView.value = 'table';
                        isLoading.value = false;
                        successNotification(result.data.message) // meggunakan swal successNotifaction dari js/helper
                        await getListUser() // agar reaktif data terupdate otomatis
                        await getListRole()
                        resetField()

                    } catch (error) {
                        if (error.response && error.response.status === 422) {
                            let responseErrors = error.response.data.errors;
                            for (let key in responseErrors) {
                                errors[key] = responseErrors[key][0];
                        }
                             isLoading.value = false;
                        } else {
                            Swal.fire('Error', error.response?.data?.message || 'Terjadi kesalahan', 'error');
                        }
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
                const getListUser = async (dataUser=`/list-user?page=${page.value}`)=> {
                    try {
                        currentView.value = 'loading-table'
                        const result = await axios.get(dataUser)
                        currentView.value = 'table'
                        users.value = result.data.data?.data || []
                        page.value = result.data.data
                        links.value = result.data.data
                    } catch (error) {
                        console.log(error)
                    }
                }

                async function toRefreshListUser() {
                    await getListUser()
                }

                const getListRole = async ()=> {
                    try {
                        const result = await axios.get('/list-role-to-user');
                        listRole.value =  result.data.data
                    } catch (error) {
                        console.log(error)
                    }

                }

                watch(search, async (newSearch) => {
                    newSearch ?  await searchUser() :  await getListUser();
                 })

                onMounted( async ()=> {
                    await getListUser(),
                    await getListRole()
                })
                return {
                    deleteConfirm, btnEditUser, editData, toRefreshListUser, listRole, users, getListUser,
                    links, search,searchUser, perPage, page,errors, roleId,
                    userId, fieldLabels, isLoading, backToPreviousPage, dataUserProfile,
                    btnShowDetailProfile, btnShowDataGeneral, cardUserDetail,
                    storeDataUserGeneral, dataUserGeneral, user, currentView,
                    showFormCreate, showFormEdit, showDetailUser, showTable,
                    closeCreateForm,
                }
            }
        }).mount('#app')
    </script>
</x-layouts.app>
