<x-layouts.app :title="__('Jurusan')" appName="Example Administrasi">
    <div id="app" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl" wire:ignore>
        @include('partials.majors-heading')

         <div class="relative h-full flex-1 overflow-hidden rounded-xl">
            <div class="flex gap-4">
                <button
                    v-show="currentView === 'table'"
                    @click="showFormCreate"
                    type="button"
                    class="text-white mb-2 inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 text-center text-xs dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
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

            <div
                v-cloak
                v-show="currentView === 'edit'"
                class="flex fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    @include('majors.with-vue._card-form-edit')
            </div>

        </div>
    </div>
    <script type="module">
        const { createApp, ref, toRefs, onMounted, reactive, watch, computed } = Vue
        createApp({
            setup() {
                const message = ref('Hello vue!')
                const listMajor = ref([])
                const listTeacher = ref([])
                const currentView = ref("table")
                const isLoading = ref(false)
                const errors = reactive({ name: '', user_id: '' })
                const isDirty = ref(false)
                const fieldLabels = { name: 'Nama', user_id: 'Kepala Jurusan' }
                const major = reactive({ name: '', user_id: '' })
                const currentMajor = ref({ name: '', user_id: '' }) // untuk tempat spread data
                const { name, user_id } = toRefs(major);
                const editDataMajor = ref({ id: '', name: '', user_id: '' })
                const showFormCreate =()=> currentView.value = 'create'

                function closeCreateForm () {
                    let isAnyFilled = Object.values(major).some( value => value !== '')

                    if (isAnyFilled) {
                        cancelConfirmation('Yakin membatalkan?', (result)=> {
                            if (result.isConfirmed) {
                                currentView.value = 'table';
                                resetField();
                                resetErrors()
                            }
                        });
                    }else {
                        currentView.value = 'table'
                        resetErrors()
                    }
                }

                function resetField() {
                    Object.assign(major, {
                        name: '',
                        user_id: '',
                    });
                }

                function resetErrors(){
                    Object.assign(errors, {
                        name: '',
                        user_id: ''
                    })
                }

                const getListMajor = async ()=> {
                    try {
                        const result = await axios.get('list-major');
                        listMajor.value = result.data.data;
                    } catch (error) {
                        console.log(error)
                    }
                }

                const getListTeacher = async()=> {
                    try {
                        let result = await axios.get('list-get-teacher')
                        listTeacher.value = result.data.data
                    } catch (error) {
                        console.log(error)
                    }
                }

                const editMajor = async (majorId)=> {
                    try {
                        let result = await axios.get(`edit-major/${majorId}`);
                        currentView.value = 'edit';
                        editDataMajor.value = result.data.data;
                        Object.assign(editDataMajor, result.data.data) // memberi data ke object
                        currentMajor.value = {...editDataMajor.value} // menyalin data
                    } catch (error) {
                        console.log('data error',error)
                    }
                }

                watch(editDataMajor,(newValue)=> {
                    isDirty.value = JSON.stringify(newValue) !== JSON.stringify(currentMajor.value)
                    }, { deep: true }
                );

                const isChanged = computed(()=> {
                    return JSON.stringify(editDataMajor.value) !== JSON.stringify(currentMajor.value)
                });

                const updateMajor = async(majorId)=> {
                     isLoading.value = true;
                    if (!isChanged.value) {
                        setTimeout(() => {
                            isLoading.value = false;
                            successNotification('nothing changed!')
                            currentView.value = 'table'
                        }, 500);
                        return
                    }

                    
                }

                function closeEditForm () {
                    if (isDirty.value) {
                        cancelConfirmation('Yakin membatalkan?', (result) => {
                            if (result.isConfirmed) {
                                resetField()
                                currentView.value = 'table'
                            }
                        })
                    } else {
                        currentView.value = 'table'
                    }
                }
                const deleteConfirmation =(majorId)=> {
                    confirmDelete('Yakin dihapus?', async (result)=>{
                        if(!result.isConfirmed) {
                            return
                        }
                        await swalLoading('Menghapus Data Jurusan..',async (result)=> {
                            try {
                                let result = await axios.delete(`/delete-major/${majorId}`)
                                successNotification(result.data.message)
                                getListMajor()
                            } catch (error) {
                                console.log('error:', error)
                                if (error.response && error.response.status == 409) {
                                        swalNotificationConflict(error.response.data.message)
                                }else {
                                    swalInternalServerError(error.response.data.message) // http code 500
                                }
                            }
                        });
                    })
                }

                const storeMajor = async()=> {
                    try {
                        let isValid = true;
                        for (let key in major) {
                            if (!major[key].toString().trim()) {
                                let label  = fieldLabels[key] || key;
                                    errors[key] = `${label} tidak boleh kosong`;
                                    isValid = false;
                            }else {
                                errors[key] = '';
                            }
                        }

                        if (!isValid) return

                        let sendMajor = {
                            'name': major.name,
                            'user_id': major.user_id,
                        }
                        console.log(sendMajor);

                        isLoading.value = true;
                        const result = await axios.post('/store-major', sendMajor)
                        isLoading.value = false
                        resetField()
                        closeCreateForm()
                        currentView.value = 'table'
                        successNotification(result.data.message)
                        getListMajor()
                    } catch (error) {
                        console.log('error', error)
                        if (error.response && error.response.status === 422) {
                            let responseErrors = error.response.data.errors;
                            for (let key in responseErrors) {
                                errors[key] = responseErrors[key][0];
                            }
                            isLoading.value = false
                        }else {
                            swalInternalServerError(error.response.data.message) // http code 500
                        }
                    }
                }
                onMounted( async()=> {
                    await getListMajor()
                    await getListTeacher()
                });
                return {
                    currentView, listMajor, isLoading, errors, fieldLabels, major, message,
                    showFormCreate, closeCreateForm, editMajor, deleteConfirmation, storeMajor,
                    listTeacher, updateMajor, closeEditForm, editDataMajor,
                }
            }
        }).mount('#app')
        </script>
</x-layouts.app>
