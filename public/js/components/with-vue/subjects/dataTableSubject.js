const { defineComponent, ref, watch, computed } = Vue
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
        }
    },
    setup(props) {
        const localListSubject = ref([])
        const colorMap = {
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

        watch(() => props.data, (newVal) => { localListSubject.value = [...newVal] }, { immediate: true });
        return {
            localListSubject, getBadgeClass
        }
    },
    template: `
       <div class="mt-2 relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex flex-wrap items-center justify-between pb-4">
                <!-- Kosongkan sisi kiri jika tidak digunakan -->
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
                            Nama Pelajaran
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Guru
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Kelas
                        </th>
                        <th scope="col" class="px-6 py-3">
                           Jumlah JP
                        </th>
                         <th scope="col" class="px-6 py-3">
                           Warna
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="data.length > 0">
                        <tr v-for="(subject , index) in data" :key="subject.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ index + 1 }}
                            </th>
                            <td class="px-6 py-4">
                                {{ subject.name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ subject.teacher?.name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ subject.classroom?.name }}-{{subject.classroom?.major.initial}}
                            </td>
                            <td class="px-6 py-4">
                                {{ subject.jumlah_jp }}
                            </td>
                            <td class="px-6 py-4">
                                <span :class="getBadgeClass(subject.colour)" class="text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm">
                                    {{ subject.colour }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            </td>
                        </tr>
                     </template>
                </tbody>
            </table>
        </div>

    `
})
