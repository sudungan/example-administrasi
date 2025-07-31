const { defineComponent, ref, reactive, watch, computed, onMounted  } = Vue
export default defineComponent({
    name: 'formCreateSubject', // nama child component,
    props: {
        visableCard: {
            type: String,
            required: true
        },
        classrooms: {
            type: Array,
            required: true
        },
        dataPassingTeacher: {
            type: [Object, null],
            required: true
        }
    },
    emit: ['backTo', 'reload'],
    setup(props, {emit}) {
        const subject = reactive({ name: '',  classroom_id: '', jumlah_jp: '' })
        const fieldLabels = { name: 'Nama', classroom_id: 'Kelas', jumlah_jp: 'Jumlah Jam Pelajaran' }
        const errors = reactive({ name: '', classroom_id: '', jumlah_jp: '' })
        const localDataTeacher = ref({...props.dataPassingTeacher})
        const localClassroom = ref(props.classrooms)
        const isLoading = ref(false)


        // melihat perubahan langsung dari props.teachers yang dikirim dari parent disimpan ke state localDataTeacher
        watch(() => props.dataPassingTeacher, (newVal) => { localDataTeacher.value = newVal }, { immediate: true });

        // melihat perubahan langsung dari props.classrooms yang dikirim dari parent disimpan ke state localClassroom
        watch(() => props.classrooms, (newVal) => { localClassroom.value = newVal }, { immediate: true });

        const closeCreateForm =()=> {
            resetFields(subject);
            resetFields(errors);
            emit('backTo', 'table')
        }

        async function storeSubject() {
            try {
                let isValid = true;
                for (let key in subject) {
                    if (!subject[key].toString().trim()) {
                        let label  = fieldLabels[key] || key;
                            errors[key] = `${label} tidak boleh kosong`;
                            isValid = false;
                    }else {
                        errors[key] = '';
                    }
                }

                if (!isValid) return // jika tidak valid / tidak terisi beberapa field akan kembali

                let sendDataSubject = {
                    name: subject.name,
                    user_id: localDataTeacher.value.user_id,
                    colour: localDataTeacher.value.colour,
                    classroom_id: subject.classroom_id,
                    jumlah_jp: subject.jumlah_jp
                }
                    isLoading.value = true;
                let result = await axios.post(`store-subject`, sendDataSubject)
                    isLoading.value = false;
                    resetFields(subject);
                    resetFields(errors);
                    successNotification(result.data.message)
                    emit('reload')
                    emit('backTo', 'table')
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

        let badgeClass = computed(() => {
            let color = localDataTeacher.value.colour
            return [
                `bg-${color}-100`, `dark:bg-${color}-900`, `dark:text-${color}-200`, `text-${color}-800`,  `border`,  `border-${color}-400`,  'text-xs',  'font-medium', 'mb-2', 'me-2',  'px-2.5', 'py-0.5','rounded-sm'
            ]
        })

        return {
            subject, closeCreateForm, badgeClass, storeSubject, errors, isLoading, dataPassingTeacher: localDataTeacher, classrooms: localClassroom,
        }
    },
    template: `
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-gray-300 rounded-lg shadow-sm dark:bg-gray-900">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl flex-inline font-semibold text-gray-900 dark:text-white dark:semibold">
                        new subject to
                        <span v-if="dataPassingTeacher" :class="badgeClass">
                        {{dataPassingTeacher.teacher.name}}
                        </span>
                    </h3>
                </div>
                 <div class="p-4 md:p-5 space-y-4">
                    <form @submit.prevent="storeSubject" class="space-y-4">
                        <div class="mb-6">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Nama Pelajaran
                            </label>
                            <input
                                type="text"
                                v-model="subject.name"
                                id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Type subject name here.."
                            >
                            <p  v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.name }}</p>
                        </div>
                        <div class="grid gap-2 mb-2 grid-cols-2">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="student_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kelas</label>
                                <select v-model="subject.classroom_id" id="classroom_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="">Select kelas</option>
                                    <option
                                        v-for="classroom in classrooms"
                                        :key="classroom.id"
                                        :value="classroom.id
                                        ">{{classroom.name}}-{{classroom.major.initial}}</option>
                                </select>
                                <p v-if="errors.classroom_id" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.classroom_id }}</p>
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="student_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah JP</label>
                                <select v-model="subject.jumlah_jp" id="jumlah_jp" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value=""> Jumlah Jam Pelajaran</option>
                                    <option v-for="index in 10" :key="index" :value="index">{{index}}</option>
                                </select>
                                <p v-if="errors.jumlah_jp" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.jumlah_jp }}</p>
                            </div>
                        </div>

                        <div class="col-span-2 flex gap-2 mt-4">
                            <button
                                :disabled="isLoading"
                                type="submit"
                                :class="{ 'opacity-50 cursor-not-allowed': isLoading }"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 inline-flex items-center"
                            >
                                    <svg v-if="isLoading" :aria-hidden="!isLoading" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                                </svg>
                                <p v-if="isLoading">
                                    process...
                                </p>
                                <p v-else> Simpan</p>
                            </button>

                            <button
                                :disabled="isLoading"
                                :class="{ 'opacity-50 cursor-not-allowed': isLoading }"
                                type="button"
                                @click="closeCreateForm"
                                class="text-white inline-flex items-center bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `
})
