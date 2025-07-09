const { defineComponent, watch, ref, onMounted, reactive  } = Vue
import multipleSelect from "./multipleSelect.js"
export default defineComponent({
    name: 'formCreateClassroom', // nama child component
    components: {
        multipleSelect,
    },
    props: {
        visableCard: {
            type: String,
            required: true
        },
        // userId: {
        //     type: Number,
        //     required: true
        // }
    },
    emits: ['backTo'],
    setup(props, {emit}) {
        const classroom = reactive({  name: '',  teacher_id: '',  student_ids: [],  major_id: ''  })
        const errors = reactive({ name: '', teacher_id: '',   major_id: '',  student_ids: ''  })
        const listMajor = ref([])
        const listTeacher = ref([])
        const listStudent = ref([])
        const isLoading = ref(false)

        onMounted(async()=> {
            await getListMajor()
            await getListTecher()
            await getListStudent()
        });


        async function getListTecher() {
            try {
                let result = await axios.get('/list-teacher')
                listTeacher.value = result.data.data
            } catch (error) {
                console.log('error', error)
            }
        }

        async function getListStudent() {
            try {
                let result = await axios.get('/list-student')
                listStudent.value = result.data.data
            } catch (error) {
                console.log('error', error)
            }
        }

         async function getListMajor() {
            try {
                let result = await axios.get('/list-major')
                listMajor.value = result.data.data
            } catch (error) {
                console.log('error', error)
            }
        }

        async function storeClassroom() {
            try {
                let sendDataClassroom = {
                    name: classroom.name,
                    teacher_id: classroom.teacher_id,
                    student_ids: classroom.student_ids,
                    major_id: classroom.major_id
                }
                let result = await axios.post('/store-classroom', sendDataClassroom)
            } catch (error) {
                console.log('error:', error)
            }
        }

        const closeCreateForm =()=> {
            emit('backTo', 'table')
        }

        return {
            storeClassroom, closeCreateForm, isLoading, classroom, listTeacher, getListTecher,
            listStudent, getListStudent, errors, listMajor, getListMajor
        }
    },
    template:  `
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-gray-300 rounded-lg shadow-sm dark:bg-gray-900">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white dark:semibold">
                        Form Crete New Classroom
                    </h3>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    <form @submit.prevent="storeClassroom" class="space-y-4">
                        <div class="grid gap-2 mb-2 grid-cols-2">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Nama Kelas
                                </label>
                                <input
                                    type="text"
                                    v-model="classroom.name"
                                    id="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Type classroom name here.."
                                >
                                 <p v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.name }}</p>
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="teacher_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Wali Kelas</label>
                               <select v-model="classroom.teacher_id" id="teacher_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="">Select homerome teacher</option>
                                    <option v-for="teacher in listTeacher" :key="teacher.id" :value="teacher.id">{{teacher.name}}</option>
                                </select>
                                <p v-if="errors.teacher_id" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.teacher_id }}</p>
                            </div>

                            <div class="col-span-2 sm:col-span-2">
                                <label for="student_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Siswa</label>
                                <multiple-select  v-model="classroom.student_ids" :options="listStudent":errors="errors"/>
                            </div>

                            <div class="col-span-2 sm:col-span-2">
                                <label for="major_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Nama Jurusan
                                </label>
                                <select v-model="classroom.major_id" id="major_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="">Select Major</option>
                                    <option v-for="major in listMajor" :key="major.id" :value="major.id">{{major.name}}</option>
                                </select>
                                 <p v-if="errors.major_id" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.major_id }}</p>
                            </div>

                            <div class="relative flex mt-2 gap-2">
                                <button
                                    :disabled="isLoading"
                                    type="submit"
                                    :class="{ 'opacity-50 cursor-not-allowed': isLoading }"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 inline-flex items-center">
                                    <svg v-if="isLoading" :aria-hidden="!isloading" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
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
