const { defineComponent, ref, watch, computed, onMounted } = Vue
export default defineComponent({
    name: 'cardCreateSchedule',
    props: {
        provideDays: {
            type: Array,
            required: true
        },waitingProcess: {
            type: Boolean,
            required: true
        },
        provideType: {
            type: Array,
            required: true
        },
        provideTime: {
            type: Array,
            required: true
        }
    },
    emits: ['backTo'],
    setup(props, {emit}) {
        const childDays = ref(props.provideDays)
        const createSchedule = ref({ day: '', classroom_id: '', time_slot_ids: [], subject_id: '', type: '' })
        const childIsLoading = ref(props.waitingProcess)
        const childTypeSchedule = ref(props.provideType)
        const childTimetable = ref(props.provideTime)
        const selectedTimeSlot = ref(null)

        watch(() => props.provideDays, (newVal) => { childDays.value = newVal }, { immediate: true });

        watch(() => props.waitingProcess, (newVal) => { childIsLoading.value = newVal }, { immediate: true });

        watch(() => props.provideType, (newVal) => { childTypeSchedule.value = newVal }, { immediate: true });

        watch(() => props.provideTime, (newVal) => { childTimetable.value = newVal }, { immediate: true });

         // melihat perubahan select2 multiple dari classroom.students_ids
        watch(() => createSchedule.time_slot_ids, (newVal) => {
            const $el = $(selectedTimeSlot.value);
            const currentVal = $el.val() || [];
            const newValStr = newVal.map(String);
            if (JSON.stringify(currentVal) !== JSON.stringify(newValStr)) {
                $el.val(newValStr).trigger('change');
            }
        });

        onMounted(() => {
            // melakakan inisialisasi dari refrensi selected ref
            const $el = $(selectedTimeSlot.value);

            // memberi nilai dari properti yang tersedia
            $el.select2({  width: '100%',   placeholder: "Pilih time slot", allowClear: true });

            // Saat user memilih dari Select2
            $el.on('change', () => {
                const selectedVal = $el.val() || [];
                createSchedule.time_slot_ids = selectedVal.map(Number); // Convert ke angka
            });
        });
        const btnCancel =()=> {
            emit('backTo', 'table')
        }
        const formatTime = (time) => time.slice(0, 5);

        async function storeSchedule() {
            console.log('data hari', createSchedule.value.day)
        }
        return {
            btnCancel, provideDays: childDays, createSchedule, storeSchedule, childIsLoading, provideType: childTypeSchedule,
            provideTime: childTimetable, formatTime, selectedTimeSlot
        }
    },
    template: `
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-gray-300 rounded-lg shadow-sm dark:bg-gray-900">
                <div class="flex items-center justify-between p-2 md:p-3 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white dark:semibold">
                        Jadwal Baru
                    </h3>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    <form @submit.prevent="storeSchedule" class="space-y-4">
                        <div class="grid gap-2 mb-2 grid-cols-2">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Tipe jadwal
                                </label>
                                <select v-model="createSchedule.type" id="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="">Pilih Tipe</option>
                                    <template v-if="provideType.type == 'break'">

                                    </template>
                                    <option v-for="type in provideType" :key="type.id" :value="type.value">
                                        {{ type.label }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="day" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hari</label>
                                <select v-model="createSchedule.day" id="day" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="">Pilih hari</option>
                                    <option v-for="day in provideDays" :key="day.id" :value="day.value">
                                        {{ day.label }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Pilih Waktu
                            </label>
                            <template v-if="provideDays.length > 0">
                                <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <li v-for="time in provideTime" :key="time.id" class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                        <div class="flex items-center ps-3">
                                            <input id="vue-checkbox-list" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label for="vue-checkbox-list" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{ formatTime(time.start_time) }} - {{ formatTime(time.end_time) }} </label>
                                        </div>
                                    </li>
                                </ul>
                            </template>
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
                                @click="btnCancel"
                                class="text-white inline-flex items-center bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                            cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `
})
