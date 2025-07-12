const { defineComponent, onMounted, ref } = Vue
export default defineComponent({
    name: 'baseLoadingTable', // nama child component
    template:  `
        <div role="status" class="w-full overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 animate-pulse">
                <!-- slot untuk skeleton -->
                <slot name="thead"></slot>
                <tbody>
                    <template v-for="n in 5" :key="n">
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <!-- slot untuk skeleton -->
                            <slot name="skeleton"/>
                        </tr>
                    </template>
                </tbody>
            </table>
            <span class="sr-only">Loading...</span>
        </div>
    `
})
