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
        let users = [
            { name: 'Neil Sims', email: 'email@windster.com', image: 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-2.jpg', amount: 320 },
            { name: 'Bonnie Green', email: 'email@windster.com', image: 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-2.jpg', amount: 3467 },
            { name: 'Michael Gough', email: 'email@windster.com', image: 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-2.jpg', amount: 67 },
        ]

        const btnBack =()=> {
            emit('backTo', 'table')
        }
        watch(() => props.data, (newVal) => { localListSubject.value = [...newVal] }, { immediate: true });
        return {
            localListSubject, users, btnBack
        }
    },
    template: `
            <div class="w-full max-w-4xl p-6 bg-white border border-gray-200 rounded-lg shadow-md sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h5 class="text-2xl font-bold text-gray-900 dark:text-white">List Subject {{data[0]?.teacher?.name}} </h5>
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
                <li v-for="(user, index) in users" :key="index" class="py-4">
                    <div class="flex items-center">
                    <div class="shrink-0">
                        <img class="w-10 h-10 rounded-full" :src="user.image" :alt="user.name + ' image'">
                    </div>
                    <div class="flex-1 min-w-0 ms-4">
                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                        {{ user.name }}
                        </p>
                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                        {{ user.email }}
                        </p>
                    </div>
                    <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                        {{ user.amount }}
                    </div>
                    </div>
                </li>
                </ul>
            </div>
        </div>
    `
})
