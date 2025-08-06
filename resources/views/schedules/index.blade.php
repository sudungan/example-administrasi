<x-layouts.app :title="__('Jadwal Mata Pelajaran')">
    <div wire:ignore class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl">
            @include('partials.schedule-heading')
            <div id="app"></div>
        </div>
    </div>
    <script type="module">
        import stateScheduleApp from '/js/components/with-vue/schedules/stateScheduleApp.js';
        stateScheduleApp()
    </script>
</x-layouts.app>
