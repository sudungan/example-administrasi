<x-layouts.app :title="__('Classroom')">
    <div id="app" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @include('partials.settings-heading')
        {{$listClassroom}}
        {{-- <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div> --}}
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>

    <script type="module">
        import { createApp, ref, reactive, onMounted   } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js'
        import axios from 'https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/esm/axios.min.js';

        createApp({
            setup() {
                const message = ref('Hello Vue!')
                // let classrooms = @json($listClassroom)
                // onMounted(()=> {
                //     console.log(classrooms)
                // })
                return {
                    message
                }
            }
        }).mount('#app')
</script> 
</x-layouts.app>