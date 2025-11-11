const { defineComponent, watch, ref, reactive }  = Vue

// membuat component dari fungsi defineComponent
export default defineComponent({
    // nama properti untuk nama komponent
    name: 'formCreateVocationalExam',

    // keyword props untuk menyelaraskan properti yang akan dipakai diparent komponent
    props: {
        visableCard: {
            type: String,
            required: true
        },
        waitingProcess: {
            type: Boolean,
            required: true
        },
    },

    // keyword emits untuk menampung nama fungsi yang akan berkomunikasi dengan fungsi component parent
    emits: ['backTo'], 

    // keyword setup untuk settingan bagian dari component child dibutuhkan
    setup(props, {emit}) {
        // membuat state untuk menampung objek vocationExam dari fungsi reactive 
        const vocationalExam = reactive({ name: '', description: '', period: '' })

        // membuat state untuk menampung objek vocationExam dari fungsi reactive 
        const errors = reactive({ name: '', period: '', description: '' })

        // membuat state untuk menampung objek vocationExam dari fungsi reactive 
        const fieldLabels = { name: 'Nama Ujian', description: 'Tema Ujian', period: 'Periode Ujian' };

        // membuat state di child component untuk mengammbil props dari parent
        const childIsLoading = ref(props.waitingProcess)

        // melihat perubahan langsung dari props.waitingProcess yang dikirim dari parent disimpan ke state childIsLoading
        watch(() => props.waitingProcess, (newVal) => { childIsLoading.value = newVal }, { immediate: true });


        // event submit untuk mengirim data ke BE
        async function storeVocationExam() {
            try {
                let isValid = true;
                childIsLoading.value = true;

                for (let key in vocationalExam) {
                    if (!vocationalExam[key].toString().trim()) {
                        let label  = fieldLabels[key] || key;
                            errors[key] = `${label} tidak boleh kosong`;
                            isValid = false;
                            childIsLoading.value = false;
                    }else {
                        errors[key] = '';
                    }
                }

                if (!isValid) return // jika tidak valid / tidak terisi beberapa field akan kembali

                let sendDataVacationExam = {
                    name: vocationalExam.name,
                    period: vocationalExam.period,
                    description: vocationalExam.description
                }
                childIsLoading.value = true;
                let result = await axios.post('/store-data-vocational-exam', sendDataVacationExam)
                resetFields(vocationalExam)
                successNotification(result.data.message)
                childIsLoading.value = false;
                emit('reload')
                emit('backTo', 'table')
            } catch (error) {
               if (error.response?.status === 422) {
                    let responseErrors = error.response.data.errors;
                        for (let key in responseErrors) {
                            errors[key] = responseErrors[key][0];
                        }
                        childIsLoading.value = false
               }else {
                childIsLoading.value = false;
                console.log('error terakhir:', error)
               }
            }
        }

        // event handler untuk close form create
        function closeCreateForm() {
           let isAnyFilled = Object.values(vocationalExam).some(value => {
                if (Array.isArray(value)) return value.length > 0
                    return value !== ''
            })

            if (isAnyFilled) {
                cancelConfirmation('Yakin membatalkan?', (result)=> {
                    if (result.isConfirmed) {
                        resetFields(vocationalExam); // reset field untuk object classroom
                        resetFields(errors); // reset field untuk object errors
                        emit('backTo', 'table')
                    }
                });
            }else {
                resetFields(vocationalExam);
                resetFields(errors);
                emit('backTo', 'table')
            }
        }

        // keyword return untuk diekspose semua state dan event handler didalam template
        return {
            vocationalExam, storeVocationExam, errors, closeCreateForm, waitingProcess: childIsLoading,
            fieldLabels
        }
    },

    // keyword template untuk tampilan halaman dari component yagn akan ditampilkan
    template: `
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-gray-300 rounded-lg shadow-sm dark:bg-gray-900">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white dark:semibold">
                        Buat Ujian Keahlian
                    </h3>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    <form @submit.prevent="storeVocationExam" class="space-y-4">
                        <div class="grid gap-2 mb-2 grid-cols-2">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Nama Ujian Kejuruan
                                </label>
                                <input
                                    type="text"
                                    v-model="vocationalExam.name"
                                    id="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Type major name here.."
                                >
                                 <p  v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.name }}</p>
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Periode Ujian</label>
                                <input
                                    type="text"
                                    v-model="vocationalExam.period"
                                    id="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="type addition role here.."
                                >
                               <p v-if="errors.period" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.period }}</p>
                            </div>

                            <div class="col-span-2">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tema Ujian</label>
                                <input 
                                    type="text" 
                                    v-model="vocationalExam.description" 
                                    id="name" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                                    placeholder="Tuliskan tema ujian.." 
                                >
                                    <p v-if="errors.description" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.description }}</p>
                            </div>

                            <div class="relative flex mt-2 gap-2">
                                <button
                                    :disabled="waitingProcess"
                                    type="submit"
                                    :class="{ 'opacity-50 cursor-not-allowed': waitingProcess }"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 inline-flex items-center">
                                    <svg v-if="waitingProcess" :aria-hidden="!waitingProcess" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                                    </svg>
                                    <p v-if="waitingProcess">
                                        process...
                                    </p>
                                    <p v-else> Simpan</p>
                                </button>
                                <button
                                    :disabled="waitingProcess"
                                    :class="{ 'opacity-50 cursor-not-allowed': waitingProcess }"
                                    type="button"
                                    @click="closeCreateForm"
                                    class="text-white inline-flex items-center bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `
})