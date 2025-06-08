import { defineComponent, ref, onMounted, watch } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';

export default defineComponent({
    name: 'pagination',
    props: {
        users: {
            type: Object,
            required: true
        }
    },
    emits: ['fetchPage'],
    setup(props , {emit}) {
        const localUsers = ref(props.users);
        const links = ref([]);
        console.log('check data dari child class', props.users)

        watch(
            ()=> props.users,
            (newValue)=> {
                console.log('update users dari child', newValue)
                localUsers.value = newValue
            },
            { immediate: true }
        );

        function btnPrevious() {
             const prevUrl = localUsers.value.data?.prev_page_url;
             console.log('btn previous ditekan...', localUsers.value.data?.prev_page_url)
                if (!prevUrl) return;
                if (prevUrl) {
                    emit('fetchPage', prevUrl)
                }
        }

        function btnNextPage() {
            console.log('btn next ditekan...', localUsers.value.data?.next_page_url)

        }
        async function goToPage(url) {
           if (!url) return;
            const res = await axios.get(url);
            localUsers.value = res.data;
        }

        return {
            users: props.users,
            btnPrevious,
            links,
            btnNextPage,
            localUsers,
            goToPage,
        };
    },
    template: `
        <nav v-if="localUsers && localUsers.data" class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4" aria-label="Table navigation">
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">
            Showing <span class="font-semibold text-gray-900 dark:text-white">  {{ localUsers.data.from }} </span>
             of <span class="font-semibold text-gray-900 dark:text-white">{{localUsers.data.total}}</span></span>
            <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">

                <li>
                    <a
                        @click="btnPrevious()"
                        class="cursor-pointer flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                </li>

                <!-- Page Numbers -->
                <li>
                <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a>
                </li>
                <li>
                    <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">2</a>
                </li>
                <li>
                    <a href="#" aria-current="page" class="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">3</a>
                </li>
                <li>
                    <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">4</a>
                </li>
                <li>
                    <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">5</a>
                </li>

                <li>
                    <a @click="btnNextPage()"
                    :disabled="!localUsers.data?.next_page_url"
                    class="cursor-pointer flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                </li>
            </ul>
        </nav>
    `
});
