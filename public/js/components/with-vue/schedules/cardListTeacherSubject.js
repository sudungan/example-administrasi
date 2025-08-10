const { defineComponent, ref, watch, computed, onMounted } = Vue
export default defineComponent({
    name: 'cardListTeacherSubject',
    props: {},
    emits: ['setting'],
    setup(props, {emit}) {
        const isOpen = ref(false)
        const listTeacherSubject = ref([])
        const colourSubject = ref("")

        onMounted(async()=> {
            await getListTeacherSubject()
        })
        const handleBtnOpen =()=> {
            isOpen.value = !isOpen.value
        }
        async function getListTeacherSubject() {
             try {
                    let result = await axios.get('list-teacher-subject'); // mengambil data guru dengan subjectnya
                    listTeacherSubject.value = result.data.data
                } catch (error) {
                    console.log('error:', error)
                }
        }

        function settingSchedule(subjectId) {
            emit('setting', {component: 'setting-schedule', subjectId: subjectId})
        }

         function useSubjectColor(subject) {
            return {
                'background-color': subject.colour + '20',
                'color': subject.colour,
                'border-color' : subject.colour           
            }
        }

        return {
            isOpen, handleBtnOpen, listTeacherSubject, getListTeacherSubject, settingSchedule, useSubjectColor
        }
    },
    template: `
        <div id="accordion-nested-parent" data-accordion="collapse">
            <h2 id="accordion-collapse-heading-1">
                <button
                    @click="handleBtnOpen"
                    type="button"
                    class="flex items-center justify-between w-full p-1 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3">
                <span>List Guru Pelajaran</span>
                <svg data-accordion-icon
                    :class="{ 'rotate-180': isOpen, 'rotate-0': !isOpen }"
                    class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                </svg>
                </button>
            </h2>
            <div v-show="isOpen">
                <div class="p-2 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Nama Guru
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                       List Pelajaran
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="listTeacherSubject.length > 0">
                                    <tr v-for="teacher in listTeacherSubject" :key="teacher.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ teacher.name }}
                                        </th>
                                        <td class="px-6 py-4">
                                            <template v-if="teacher.subjects && teacher.subjects.length">
                                                <div v-for="subject in teacher.subjects" :key="subject.id" class="inline-flex mb-2">
                                                    <!-- Badge isi subject -->
                                                    <span
                                                        :style="useSubjectColor(subject)"
                                                        class="me-2 cursor-pointer underline border rounded-sm text-xs font-medium me-2 px-2.5 py-0.5 hover:text-blue-500 hover:font-bold">
                                                        <p @click="settingSchedule(subject.id)" class="me-2 cursor-pointer underline hover:text-blue-500 hover:font-bold">  {{ subject.name }} </p>
                                                    </span>
                                                </div>
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>

                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    `
})
