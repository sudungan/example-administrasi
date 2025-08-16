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

        return {
            badgeClass, localListSubject, btnBack,
        }
    },
    template: `
        <div class="w-full max-w-4xl p-6 bg-white border border-gray-200 rounded-lg shadow-md sm:p-8 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h5 class="text-2xl font-bold text-gray-900 dark:text-white">List Subject  {{data[0]?.teacher?.name}} </h5>
                <a @click="btnBack" data-tooltip-target="tooltip-right" data-tooltip-placement="right"  class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">
                <svg class="w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
                </svg>
                </a>
                <div id="tooltip-right" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                    Back
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </div>

            <div class="flow-root">
                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li v-for="(subject, index) in localListSubject" :key="index" class="py-4">
                        <div class="flex items-center">
                            <div class="flex-1 min-w-0 ms-4">
                                <p class="text-sm font-medium text-gray-900 mb-2 truncate dark:text-white leading-relaxed">
                                    <span :class="badgeClass(subject)">
                                        {{ subject.name }}
                                    </span>
                                </p>
                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                    {{ subject.classroom.name }}-{{subject.classroom.major.initial}}
                                </p>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                {{ subject.jumlah_jp }} JP
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    `
})
