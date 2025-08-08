const { defineComponent, ref, reactive, watch, computed } = Vue

export default defineComponent({
    name: 'cardSettingTimeTable',
    props: {
        visableCard: {
            type: String,
            required: true
        },
        waitingProcess: {
            type: Boolean,
            required: true
        },
        provideData: {
            type: Object,
            required:true
        }
     },
    emits: ['backTo', 'reload'],
    setup(props, {emit}){
        const editTimeSlot = reactive({ start_time: '', end_time: '', activity: '',  category: '' }) // tempat menampung data edit time yang baru
        const fieldLabels = { start_time: 'waktu awal', end_time: 'waktu akhir', activity: 'aktifitas', category: 'Kategory' }
        const errors = reactive({start_time: '',  end_time: '', activity: '', category: ''})
        const localTimeSlot = ref({ ...props.provideData }) // melakukan clone data passing dari parent
        const childIsLoading = ref(props.waitingProcess) // passing data dari state parent
        const isLoading = ref(false) // state untuk handle proses pengiriman data ke BE
        const changeStartTime = useFormattedTime('start_time');
        const changeEndTime = useFormattedTime('end_time');
        let optionCategories = [{ id: 1, name: 'all day', key: 'all_day'}, {id: 2, key: 'some_day', name: 'some day' }]

        // melihat perubahan yang terjadi real-time ke child
        watch(() => props.waitingProcess, (newVal) => { childIsLoading.value = newVal }, { immediate: true });

         // melihat perubahan langsung dari props.students yang dikirim dari parent disimpan ke state childStudents
        watch(() => props.provideData, (newVal) => { Object.assign(editTimeSlot, newVal)  }, { immediate: true });

        function useFormattedTime(field) {
            return computed({
                get() {
                    return editTimeSlot[field] ? editTimeSlot[field].slice(0, 5) : '';
                },
                set(value) {
                    editTimeSlot[field] = value ? `${value}:00` : '';
                }
            });
        }


        async function updateTime() {
            try {
                let isValid = true;
                childIsLoading.value = true;
                for (let key in editTimeSlot) {
                    if (!editTimeSlot[key].toString().trim()) {
                        let label  = fieldLabels[key] || key;
                            errors[key] = `${label} tidak boleh kosong`;
                            isValid = false;
                            childIsLoading.value = false;
                    }else {
                        errors[key] = '';
                    }
                }

                if (!isValid) return

                let sendTimeSlot = {
                    id: editTimeSlot.id,
                    start_time: editTimeSlot.start_time,
                    end_time: editTimeSlot.end_time,
                    activity: editTimeSlot.activity,
                    category: editTimeSlot.category
                }

                let result = await axios.put(`/edit-time-by/${editTimeSlot.id}`, sendTimeSlot)
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
               }
            }
        }

        function btneCancelForm() {
            emit('backTo', 'table')
        }

        return {
            waitingProcess: childIsLoading, btneCancelForm, editTimeSlot, updateTime, isLoading, fieldLabels, errors,
            editTimeSlot, provideData: localTimeSlot, optionCategories, useFormattedTime, changeEndTime, changeStartTime
        }
    },
    template: `
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-gray-300 rounded-lg shadow-sm dark:bg-gray-900">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white dark:semibold">
                        Setting Time
                    </h3>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    <form @submit.prevent="updateTime" class="space-y-4">
                        <div class="grid gap-2 mb-2 grid-cols-2">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="start_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"> Start Time</label>
                                <input
                                    type="time"
                                    id="name"
                                    v-model="changeStartTime"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="HH:MM"
                                >
                                <p  v-if="errors.start_time" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.start_time }}</p>
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="end_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End Time</label>
                                 <input
                                    type="time"
                                    id="name"
                                    v-model="changeEndTime"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                   placeholder="HH:MM"
                                >
                                <p  v-if="errors.end_time" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.end_time }}</p>
                            </div>


                            <div class="col-span-2 sm:col-span-1">
                                <label for="activity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Activity name</label>
                                <input
                                    type="text"
                                    id="name"
                                    v-model="editTimeSlot.activity"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="ketik nama kegiatan disini.."
                                >
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category time</label>
                                <select v-model="editTimeSlot.category" id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="">Select category</option>
                                    <option v-for="category in optionCategories" :value="category.key" :key="category.id"> {{category.name}} </option>
                                </select>
                                <p  v-if="errors.category" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.category }}</p>
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
                                    @click="btneCancelForm"
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
});
