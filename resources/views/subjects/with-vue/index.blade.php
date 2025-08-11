<x-layouts.app :title="__('Mata Pelajaran')">
    <div wire:ignore class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div  class="relative h-full flex-1 overflow-hidden rounded-xl">
            @include('partials.subjects-heading')
            <div id="app"></div>
        </div>
    </div>
    <script type="module">
        import stateSubjectApp from '/js/components/with-vue/subjects/stateSubjectApp.js'
        stateSubjectApp()
    </script>
</x-layouts.app>
