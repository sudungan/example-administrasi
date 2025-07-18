<x-layouts.app :title="__('Kelas')">
    <div wire:ignore class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl">
            @include('partials.classrooms-heading')
            <div id="app"></div>
        </div>
    </div>
    <script type="module">
        import stateClassroom from '/js/components/with-vue/classrooms/stateClassroom.js';
        stateClassroom()
    </script>
</x-layouts.app>
