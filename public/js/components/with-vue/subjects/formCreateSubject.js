const { defineComponent, ref, reactive, watch, computed, onMounted  } = Vue
export default defineComponent({
    name: 'formCreateSubject', // nama child component,
    props: {
        visableCard: {
            type: String,
            required: true
        },
        teachers: {
            type: Array,
            required: true
        },
        classrooms: {
            type: Array,
            required: true
        },
        provideColour: {
            type: Array,
            required: true
        }
    },
    emit: ['backTo'],
    setup(props, {emit}) {
        const subject = reactive({ name: '', teacher_id: '', classroom_id: '', colour: '', jumlah_jp: '' })
        const errors = reactive({ name: '', teacher_id: '', classroom_id: '', colour: '', jumlah_jp: '' })
        const localTeacher = ref(props.teachers)
        const localClassroom = ref(props.classrooms)
        const localColours = ref(props.provideColour)
        const isLoading = ref(false)
        let badgeClass = computed(() => {
            if(!subject.colour) {
                console.log('ada dipilih')
            }
            let color = subject.colour
            return [
                `bg-${color}-100`, `dark:bg-${color}-900`, `dark:text-${color}-300`, `text-${color}-800`,  `border`,  `border-${color}-400`,  'text-xs',  'font-medium', 'me-2',  'px-2.5', 'py-0.5','rounded-sm'
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

        // melihat perubahan langsung dari props.classrooms yang dikirim dari parent disimpan ke state localClassroom
        watch(() => props.classrooms, (newVal) => { localClassroom.value = newVal }, { immediate: true });

        // melihat perubahan langsung dari props.classrooms yang dikirim dari parent disimpan ke state localColours
        watch(() => props.provideColour, (newVal) => { localColours.value = newVal }, { immediate: true });
        const closeCreateForm =()=> {
            emit('backTo', 'table')
        }

        async function storeSubject() {
            try {
                let sendDataSubject = {
                    name: subject.name,
                    teacher_id: subject.teacher_id,
                    classroom_id: subject.classroom_id,
                    colour: subject.colour,
                    jumlah_jp: subject.jumlah_jp
                }
                    isLoading.value = true;
                let result = await axios.post(`store-subject`, sendDataSubject)
                    isLoading.value = false;
            } catch (error) {

            }
        }
        return {
            subject, closeCreateForm, storeSubject, errors, isLoading, teachers: localTeacher, classrooms: localClassroom,
            provideColour: localColours, badgeClass, baseCssColour
        }
    },
    template: `
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-gray-300 rounded-lg shadow-sm dark:bg-gray-900">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white dark:semibold">
                        New Subject
                    </h3>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    <form @submit.prevent="storeSubject" class="space-y-4">
                        <div class="grid gap-2 mb-2 grid-cols-2">
                            <div class="col-span-2 sm:col-span-1">
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

                            <div class="col-span-2 sm:col-span-1">
                                <label for="teacher_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Guru</label>
                                <select v-model="subject.teacher_id" id="teacher_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="">Select guru</option>
                                    <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">{{teacher.name}}</option>
                                </select>
                                <p v-if="errors.teacher_id" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.teacher_id }}</p>
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="student_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kelas</label>
                                <select v-model="subject.classroom_id" id="classroom_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="">Select kelas</option>
                                    <option
                                        v-for="classroom in classrooms"
                                        :key="classroom.id"
                                        :value="classroom.id
                                        ">{{classroom.name}}</option>
                                </select>
                                <p v-if="errors.classroom_id" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.teacher_id }}</p>
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
                        <div class="grid-cols-1">
                            <label for="colour" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Warna
                            </label>
                                <select v-model="subject.colour" id="colour" class="bg-gray-50 border border-gray-300 w-full text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="">Select Colour</option>
                                    <option
                                        v-for="colour in provideColour"
                                        :key="colour.id"
                                        :value="colour.item"
                                        :style="{
                                                    backgroundColor: baseCssColour[colour.item] || 'transparent',
                                                    color: 'white',
                                                    padding: '8px 12px'
                                                }"
                                    >
                                    {{colour.item}}
                                    </option>
                                </select>

                                <span v-if="subject.colour" :class="badgeClass">
                                    {{ subject.colour }}
                                </span>
                            </div>
                            <div class="col-span-2 flex gap-2 mt-4">
                                <button
                                    :disabled="isLoading"
                                    type="submit"
                                    :class="{ 'opacity-50 cursor-not-allowed': isLoading }"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 inline-flex items-center"
                                >
                                    <svg v-if="isLoading" :aria-hidden="!isLoading" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none">
                                    <!-- SVG loading -->
                                    </svg>
                                    <p v-if="isLoading">process...</p>
                                    <p v-else>Simpan</p>
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
