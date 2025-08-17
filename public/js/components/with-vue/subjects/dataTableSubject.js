const { defineComponent, ref, watch, computed, onMounted } = Vue
export default defineComponent({
    name: 'dataTableSubject', // nama child component
    props: {
        data: { // nama properti yang akan digunakan child saja
            type: Array,
            required: true
        },
        visableCard: {
            type: String,
            required: true
        },
        subjectTeacherBy: {
            type: Object || null,
            required: true
        }
    },
    emits: ['add', 'show', 'reload', 'addSubjectTo'],
    setup(props, {emit}) {
        const localListSubject = ref([])
        const childTotalJp = ref({...props.subjectTeacherBy})
        const colorMap = {
            // gap-2 inline-flex bg-gray-100 text-gray-800 text-xs font-medium items-center px-2.5 py-0.5 rounded-sm me-2 dark:bg-gray-700 dark:text-gray-400 border border-gray-500 mb-2
            blue: 'bg-blue-100 text-blue-800 border border-blue-400 dark:text-blue-400 dark:bg-blue-900',
            gray: 'bg-gray-100 text-gray-800 border border-gray-400 dark:text-gray-400 dark:bg-gray-900',
            red: 'bg-red-100 text-red-800 border border-red-400 dark:text-red-400 dark:bg-red-900',
            green: 'bg-green-100 text-green-800 border border-green-400 dark:text-green-400 dark:bg-green-900',
            yellow: 'bg-yellow-100 text-yellow-800 border border-yellow-400 dark:text-yellow-400 dark:bg-yellow-900',
            indigo: 'bg-indigo-100 text-indigo-800 border border-indigo-400 dark:text-indigo-400 dark:bg-indigo-900',
            purple: 'bg-purple-100 text-purple-800 border border-purple-400 dark:text-purple-400 dark:bg-purple-900',
            pink: 'bg-pink-100 text-pink-800 border border-pink-400 dark:text-pink-400 dark:bg-pink-900',
            lime: 'bg-lime-100 text-lime-800 border border-lime-400 dark:text-lime-400 dark:bg-lime-900',
            rose: 'bg-rose-100 text-rose-800 border border-rose-400 dark:text-rose-400 dark:bg-rose-900',
            cyan: 'bg-cyan-100 text-cyan-800 border border-cyan-400 dark:text-cyan-400 dark:bg-cyan-900',
            emerald: 'bg-emerald-100 text-emerald-800 border border-emerald-400 dark:text-emerald-400 dark:bg-emerald-900',
            violet: 'bg-violet-100 text-violet-800 border border-violet-400 dark:text-violet-400 dark:bg-violet-900',
            sky: 'bg-sky-100 text-sky-800 border border-sky-400 dark:text-sky-400 dark:bg-sky-900'
        }
        function getBadgeClass(colour) {
            return colorMap[colour] || 'bg-gray-100 text-gray-800 border border-gray-400'
        }

        function addSubjectTeacher(teacherId) {
            emit('add', {  component: 'create-subject',  teacherId: teacherId })
        }

        function deleteConfirmation(subjectId, classroomId) {
            confirmDelete('Yakin dihapus?', async (result)=>{
                if(!result.isConfirmed) {
                    return
                }
                await swalLoading('delete process..',async (result)=> {
                    try {
                        let result = await axios.delete(`/delete-subject-by/${subjectId}/${classroomId}`)
                        successNotification(result.data.message)
                        emit('reload')
                    } catch (error) {
                        if (error.response && error.response.status == 409) {
                            swalNotificationConflict(error.response.data.message)
                        }else {
                            swalInternalServerError(error.response.data.message) // http code 500
                        }
                    }
                });
            })
        }

        function editSubject(subjectId) {
            console.log('subjectId', subjectId)
        }

        function btnAddSubjectTo(subjectId, subjectName, teacherName) {
            emit('addSubjectTo', {component: 'add-subject-to', subjectId: subjectId, subjectName: subjectName, teacherName: teacherName})
        }

        function showAllsubject(teacherId) {
            emit('show', {  component: 'show-list-subject-by',  teacherId: teacherId })
        }
        const getTotalJpByTeacher  = (teacherId)=> {
            emit('getTotalJpBy', teacherId)
        }

        watch(() => props.data, (newVal) => { localListSubject.value = [...newVal] }, { immediate: true });

        watch(() => props.subjectTeacherBy, (newVal) => { Object.assign(childTotalJp.value, newVal)  }, { immediate: true });
        return {
            localListSubject, getBadgeClass, addSubjectTeacher, showAllsubject, deleteConfirmation, editSubject,
            btnAddSubjectTo, subjectTeacherBy: childTotalJp
        }
    },
    template: `
       <div class="mt-2 relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex flex-wrap justify-between pb-4">
             <div></div>
                <!-- Search Input di Pojok Kanan -->
                <div class="relative">
                    <label for="table-search" class="sr-only">Search</label>
                    <div class="absolute inset-y-0 left-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input type="text" id="table-search"
                        class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Search for items">
                </div>
            </div>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                           #
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Guru
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Pelajaran
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Total JP
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="data.length > 0">
                        <tr v-for="(teacher , index) in data" :key="teacher.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ index + 1 }}
                            </th>
                            <td class="px-6 py-4">
                                <button @click="addSubjectTeacher(teacher.id)" class="text-blue-600 underline cursor-pointer hover:text-blue-800">
                                    {{ teacher.name }}
                                 </button>
                            </td>
                            <td class="px-6 py-4">
                                <template v-if="teacher.subjects && teacher.subjects.length">
                                    <div v-for="subject in teacher.subjects" :key="subject.id" class="inline-flex">
                                        <template  v-for="classroom in subject.classroom_subject" :key="classroom.id">
                                            <span
                                                class="gap-2 inline-flex text-xs font-medium items-center px-2.5 py-0.5 rounded-sm me-2 border mb-2"
                                                :class="getBadgeClass(subject.colour)"
                                                >
                                                <a @click= "btnAddSubjectTo(subject.id, subject.name, teacher.name)" class="hover:underline cursor-pointer">
                                                    {{ subject.name }}
                                                </a>
                                                {{ classroom.name }}
                                                {{ classroom.major?.initial }}
                                                ({{ classroom.pivot?.jumlah_jp }} JP)
                                                <svg  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="gray" class="cursor-pointer size-3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                                <svg @click="deleteConfirmation(subject.id, classroom.id)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="cursor-pointer size-3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </span>
                                        </template>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="flex w-auto items-start p-2 mb-4 text-sm text-yellow-800 bg-transparent dark:text-yellow-300" role="alert">
                                        <div>
                                        <span class="font-medium">Data Mata Pelajaran Belum ada...</span>
                                        </div>
                                    </div>
                                </template>
                            </td>
                            <td class="px-6 py-4">
                                <template v-if="teacher.id == subjectTeacherBy.user_id">
                                    {{ subjectTeacherBy.total_jp }}
                                </template>
                            </td>
                        </tr>
                     </template>
                </tbody>
            </table>
        </div>

    `
})
