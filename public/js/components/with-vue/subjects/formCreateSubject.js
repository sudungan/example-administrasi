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
        const subjectClassroom = reactive({ name: '',  classrooms_subject: [{classroom_id: '', jumlah_jp: ''}] })
        const fieldLabels = { name: 'Nama', classroom_id: 'Kelas', jumlah_jp: 'Jumlah Jam Pelajaran' }
        // const errors = reactive({ name: '',  classrooms_subject: [{classroom_id: '', jumlah_jp: ''}] })
        const errors = reactive({
            name: '',
            classrooms_subject: subjectClassroom.classrooms_subject.map(() => ({
                classroom_id: '',
                jumlah_jp: ''
            }))
        });
        const localDataTeacher = ref({...props.dataPassingTeacher})
        const localClassroom = ref(props.classrooms)
        const isLoading = ref(false)
        const disabledButton = ref(false)

        // melihat perubahan langsung dari props.teachers yang dikirim dari parent disimpan ke state localDataTeacher
        watch(() => props.dataPassingTeacher, (newVal) => { localDataTeacher.value = newVal }, { immediate: true });

        // melihat perubahan langsung dari props.classrooms yang dikirim dari parent disimpan ke state localClassroom
        watch(() => props.classrooms, (newVal) => { localClassroom.value = newVal }, { immediate: true });

        watch(subjectClassroom, (newVal) => { disabledButton.value = ! Object.values(newVal).every(value => {
                if (Array.isArray(value)) {
                    return value.some(item => item.classroom_id && item.jumlah_jp);
                }
                return !value.toString().trim();
            })
        }, { deep: true, immediate: true });


        const closeCreateForm =()=> {
            // todo-list: memperbaiki reset all errrors dan reset all field
            emit('backTo', 'table')
        }

        async function storeSubject() {
            try {
                let isValid = true;
                for (let key in subjectClassroom) {
                    const value = subjectClassroom[key];
                    if(Array.isArray(value)) {
                        value.forEach((item, index)=> {
                            if (!errors.classrooms_subject[index]) {
                                errors.classrooms_subject[index] = { classroom_id: '', jumlah_jp: '' };
                            }

                            if (!item.classroom_id) {
                                errors.classrooms_subject[index].classroom_id = "Kelas wajib diisi";
                                console.log('item classroom_id', item.classroom_id, 'index', index)
                                isValid = false;
                            }
                            if (!item.jumlah_jp) {
                                errors.classrooms_subject[index].jumlah_jp = "Jumlah JP wajib diisi";
                                console.log('item jumlah_jp', item.jumlah_jp, 'index', index)
                                isValid = false;
                            }
                        })
                    } else {
                        if (!value.toString().trim()) {
                            const label = fieldLabels[key] || key;
                            errors[key] = `${label} tidak boleh kosong`;
                            isValid = false;
                        } else {
                            errors[key] = '';
                        }
                    }
                }

                if (!isValid) return // jika tidak valid / tidak terisi beberapa field akan kembali

                let sendDataSubject = {
                    name: subjectClassroom.name,
                    user_id: localDataTeacher.value.user_id,
                    colour: localDataTeacher.value.colour,
                    classrooms_subject: subjectClassroom.classrooms_subject,
                    classroom_id: subject.classroom_id,
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

        function addSubjectClassroom() {
            subjectClassroom.classrooms_subject.push({classroom_id: '', jumlah_jp: ''})
        }

        const isAllFilled  = computed(() => {
            return Object.values(subjectClassroom).every(item => {
                if (Array.isArray(item)) {
                    return item.every(item => item.classroom_id && item.jumlah_jp);
                }
                return item && item.toString().trim() !== '';
            })
        });

        function removeSubjectClassroom(index) {
            subjectClassroom.classrooms_subject.splice(index, 1)
        }

        let badgeClass = computed(() => {
            let color = localDataTeacher.value.colour
            return [
                `bg-${color}-100`, `dark:bg-${color}-900`, `dark:text-${color}-200`, `text-${color}-800`,  `border`,  `border-${color}-400`,  'text-xs',  'font-medium', 'mb-2', 'me-2',  'px-2.5', 'py-0.5','rounded-sm'
            ]
        })


        return {
            subject, closeCreateForm, badgeClass, storeSubject, errors, isLoading, dataPassingTeacher: localDataTeacher, classrooms: localClassroom,
            addSubjectClassroom, subjectClassroom, removeSubjectClassroom, disabledButton, isAllFilled
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
                        <div class="mb-2">
                            <label for="name" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                                Nama Pelajaran
                            </label>
                            <input
                                type="text"
                                v-model="subjectClassroom.name"
                                id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Type subject name here.."
                            >
                            <p  v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.name }}</p>
                        </div>

                        <div class="mb-2 w-full inline space-x-4 space-x-reverse">
                            <table class="w-full text-sm rounded text-left text-gray-400 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 bg-transparent dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 text-sm font-medium text-gray-900 dark:text-white">
                                            Jadwal Kelassa
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(item, index) in subjectClassroom.classrooms_subject" :key="index">
                                        <tr class="bg-transparent">
                                            <td class="px-1 py-1">
                                                <select v-model="item.classroom_id" id="classroom_id"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                        focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400
                                                        dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                    <option value="">Select kelas</option>
                                                    <option v-for="classroom in classrooms"
                                                            :key="classroom.id"
                                                            :value="classroom.id">
                                                        {{classroom.name}}-{{classroom.major.initial}}
                                                    </option>
                                                </select>
                                                <p v-if="errors.classrooms_subject[index]?.classroom_id" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.classrooms_subject[index].classroom_id }}</p>
                                            </td>
                                            <td class="px-1 py-1">
                                                <select v-model="item.jumlah_jp" id="jumlah_jp"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                        focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400
                                                        dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                    <option value="">Jumlah Jam Pelajaran</option>
                                                    <option v-for="index in 10" :key="index" :value="index">{{ index }}</option>
                                                </select>
                                                <p v-if="errors.classrooms_subject[index]?.jumlah_jp" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.classrooms_subject[index].jumlah_jp }}</p>
                                            </td>
                                            <td class="px-1 py-1 item-right">
                                                <button type="button"
                                                    v-if="subjectClassroom.classrooms_subject.length > 1"
                                                    @click="removeSubjectClassroom(index)"
                                                    class="p-1 text-white hover:cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                             <button v-if="index === subjectClassroom.classrooms_subject.length - 1 && isAllFilled"
                                type="button"
                                @click="addSubjectClassroom"
                                class="p-1 text-white hover:cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0
                                            9 9 0 0 1 18 0Z" />
                                </svg>
                            </button>
                        </div>

                        <div class="col-span-2 flex gap-2 mt-2">
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
