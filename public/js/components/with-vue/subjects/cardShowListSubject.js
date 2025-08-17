const { defineComponent, ref, watch, computed } = Vue
export default defineComponent({
    name: 'cardShowListSubject', // nama child component
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
    emits: ['backTo'],
    setup(props, {emit}) {
        const localListSubject = ref([])
        watch(() => props.data, (newVal) => { localListSubject.value = [...newVal] }, { immediate: true });

        const btnBack =()=> {
            emit('backTo', 'table')
        }

        const badgeClass = (subject) => {
            const color = subject.colour || 'gray' // fallback jika tidak ada warna
            return [
                `bg-${color}-100`,
                `text-${color}-800`,
                `dark:bg-${color}-900`,
                `dark:text-${color}-400`,
                `border`,
                `border-${color}-400`,
                `text-xs`,
                `font-medium`,
                'mb-2',
                `me-2`,
                `px-2.5`,
                `py-0.5`,
                `rounded-sm`
            ]
        }

        const returnKeyLoop = (indexSubject, indexClassroom)=> {
            return indexSubject - indexClassroom
        }

        const divideSubjectClassroom = (index)=> {
            return index + 1 ? 'divide-y divide-gray-200 dark:divide-gray-700' : ''
        }
        return {
            badgeClass, localListSubject, btnBack, divideSubjectClassroom, returnKeyLoop
        }
    },
    template: `


<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
    <caption class="p-2 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
            {{data[0]?.teacher?.name}}
        </caption>
        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3 rounded-s-lg">
                   Mata Pelajaran
                </th>
                <th scope="col" class="px-6 py-3">
                   Kelas
                </th>
                 <th scope="col" class="px-6 py-3 rounded-e-lg">
                    JP
                </th>
            </tr>
        </thead>
        <tbody>
            <template v-for="(subject, sIndex) in localListSubject" :key="sIndex">
                <tr class="bg-white dark:bg-gray-800">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Apple MacBook Pro 17"
                </th>
                <td class="px-6 py-4">
                    1
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
            </tr>
            </template>
        </tbody>
        <tfoot>
            <tr class="font-semibold text-gray-900 dark:text-white">
                <th scope="row" class="px-6 py-3 text-base">Total</th>
                <td class="px-6 py-3">3</td>
            </tr>
        </tfoot>
    </table>
</div>

    `
})
