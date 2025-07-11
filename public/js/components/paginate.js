const { defineComponent } = Vue

export default defineComponent({
    name: 'pagination', // nama child component
    props: {
        dataUser: { // nama objek yang akan digunakan parent
            type: Object,
            required: true
        }
    },
    emits: ['paginate'], // nama event yang akan digunakan ke parent
    setup(props, { emit }) {
        function btnChangePage(link) {
            if (link.url !== null) {
                emit('paginate', link.url) // membuat nama emit dan mengirimkan object ke parent
            }
        }

        return {
            btnChangePage
        }
    },
    template:
    `
     <nav class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4" aria-label="Table navigation">
        <span class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">Showing <span class="font-semibold text-gray-900 dark:text-white">{{dataUser.from}} -{{dataUser.to}}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{dataUser.total}}</span></span>
       <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
            <li v-for="(link, index) in dataUser.links" :key="index">
                <a
                    href="#"
                    v-html="link.label"
                    @click.prevent="btnChangePage(link)"
                    :class="[
                        'flex items-center justify-center px-3 h-8 leading-tight border border-gray-300',
                        link.active ? 'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700' : 'text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700',
                        !link.url ? 'pointer-events-none cursor-not-allowed opacity-50' : '',
                        'dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white'
                    ]"
                ></a>
            </li>
        </ul>
    </nav>
    `
});
