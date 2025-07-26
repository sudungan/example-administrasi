const { defineComponent, ref, reactive, watch, computed, onMounted  } = Vue
export default defineComponent({
    name: 'createTeacherColour', // nama child component,
    props: {
        visableCard: {
            type: String,
            required: true
        },
        teachers: {
            type: Array,
            required: true
        },
        provideColour: {
            type: Array,
            required: true
        },
        dataPassingTeacher: {
            type: Object,
            required: true
        }
    },
    emit: ['changeTo','backTo', 'sendBackData'],
    setup(props, {emit}) {
        const teacherColour = reactive({ user_id:'', colour: '' })
        const fieldLabels = { name: 'Nama', user_id: 'Guru', colour: 'Warna' }
        const errors = reactive({ user_id: '', colour: '' })
        const localTeacher = ref(props.teachers)
        const localColours = ref(props.provideColour)
        const localDataPassingTeacher = ref({...props.dataPassingTeacher})
        const isLoading = ref(false)
        let badgeClass = computed(() => {
            if(!teacherColour.colour) {
                console.log('tidak memiliki')
            }
            let color = teacherColour.colour
            return [
                `bg-${color}-100`, `dark:bg-${color}-900`, `dark:text-${color}-300`, `text-${color}-800`,  `border`,  `border-${color}-400`,  'text-xs',  'font-medium', 'mb-2', 'me-2',  'px-2.5', 'py-0.5','rounded-sm'
            ]
        })

        const baseCssColour = {
            blue: '#3b82f6',
            gray: '#6b7280',
            red: '#ef4444',
            green: '#10b981',
            yellow: '#facc15',
            indigo: '#6366f1',
            purple: '#8b5cf6',
            pink: '#ec4899',
            lime: '#84cc16',
            rose: '#f43f5e',
            cyan: '#06b6d4',
            emerald: '#10b981',
            violet: '#8b5cf6',
            sky: '#0ea5e9'
        }

        // melihat perubahan langsung dari props.teachers yang dikirim dari parent disimpan ke state localTeacher
        watch(() => props.teachers, (newVal) => { localTeacher.value = newVal }, { immediate: true });

        // melihat perubahan langsung dari props.classrooms yang dikirim dari parent disimpan ke state localColours
        watch(() => props.provideColour, (newVal) => { localColours.value = newVal }, { immediate: true });

        // melihat perubahan langsung dari props.classrooms yang dikirim dari parent disimpan ke state localColours
        watch(() => props.dataPassingTeacher, (newVal) => { localDataPassingTeacher.value = newVal }, { immediate: true });
        function closeCreateForm() {
            resetFields(teacherColour);
            resetFields(errors);
            emit('backTo', 'table')
        }

        async function storeTeacherColour() {
            try {
                let isValid = true;
                for (let key in teacherColour) {
                    if (!teacherColour[key].toString().trim()) {
                        let label  = fieldLabels[key] || key;
                            errors[key] = `${label} tidak boleh kosong`;
                            isValid = false;
                    }else {
                        errors[key] = '';
                    }
                }

                if (!isValid) return // jika tidak valid / tidak terisi beberapa field akan kembali

                let sendTeacherColour = {
                    user_id: teacherColour.user_id,
                    colour: teacherColour.colour,
                }
                    isLoading.value = true;
                let result = await axios.post(`store-teacher-colour`, sendTeacherColour)
                    localDataPassingTeacher.value = result.data.data
                    isLoading.value = false;
                    resetFields(teacherColour);
                    resetFields(errors);
                    emit('changeTo', 'create-subject')
                    emit('sendBackData', localDataPassingTeacher.value)
            } catch (error) {
                console.log('error dari create subject', error)
                if (error.response && error.response.status === 422) {
                    let responseErrors = error.response.data.errors;
                    for (let key in responseErrors) {
                        errors[key] = responseErrors[key][0];
                    }
                }
                 isLoading.value = false;
            }
        }

        return {
            closeCreateForm, storeTeacherColour, errors, isLoading, teachers: localTeacher,
            provideColour: localColours, badgeClass, baseCssColour, teacherColour, dataPassingTeacher: localDataPassingTeacher
        }
    },
    template: `
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-gray-300 rounded-lg shadow-sm dark:bg-gray-900">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white dark:semibold">
                        New Base Teacher Colour
                    </h3>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    <form @submit.prevent="storeTeacherColour" class="space-y-4">
                        <div class="grid gap-2 mb-2 grid-cols-2">

                            <div class="col-span-2 sm:col-span-1">
                                <label for="user_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Guru</label>
                                <select v-model="teacherColour.user_id" id="user_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="">Select teacher</option>
                                    <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">{{teacher.name}}</option>
                                </select>
                                <p v-if="errors.user_id" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.user_id }}</p>
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="colour" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Warna dasar</label>
                                <select v-model="teacherColour.colour" id="colour" class="bg-gray-50 border border-gray-300 w-full text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="">Select Colour</option>
                                    <option
                                        v-for="colour in provideColour"
                                        :key="colour.id"
                                        :value="colour.item"
                                        :style="{ backgroundColor: baseCssColour[colour.item] || 'transparent', color: 'white', padding: '8px 12px' }"
                                    > {{colour.item}}
                                    </option>
                                </select>
                                <p v-if="errors.colour" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.colour }}</p>
                            </div>
                        </div>
                        <div class="col-span-2 flex gap-2 mt-4">
                            <button :disabled="isLoading"  type="submit" :class="{ 'opacity-50 cursor-not-allowed': isLoading }"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 inline-flex items-center"
                            >
                                <svg v-if="isLoading" :aria-hidden="!isLoading" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                                </svg>
                                <p v-if="isLoading"> process...  </p>
                                <p v-else> Simpan</p>
                            </button>

                            <button :disabled="isLoading"  :class="{ 'opacity-50 cursor-not-allowed': isLoading }"  type="button"  @click="closeCreateForm"
                                class="text-white inline-flex items-center bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
                            >  Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `
})
