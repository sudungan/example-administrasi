<x-layouts.app :title="__('Classroom')">
    <div id="classroom-app" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @include('partials.settings-heading')
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                @{{message}}
                {{-- <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" /> --}}
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>

    <script type="module">
         const { createApp, ref, toRefs, reactive, onMounted, watch } = Vue

        createApp({
            setup() {
                const message = ref('Hello Vue!')
                const listClassroom = ref([])

                onMounted(async ()=> {
                    await getListClassroom()
                });
                async function getListClassroom() {
                    try {
                        const result = await axios.get('get-list-classroom');
                        listClassroom.value = result.data.data
                    } catch (error) {
                        console.log(error)
                    }
                }
                return {
                    message, listClassroom
                }
            }
        }).mount('#classroom-app')
    </script>
</x-layouts.app>
