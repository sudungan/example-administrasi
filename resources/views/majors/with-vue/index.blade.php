<x-layouts.app :title="__('Jurusan')" appName="Example Administrasi">
    <div wire:ignore class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl">
            @include('partials.majors-heading')
            <div id="app"></div>
        </div>
    </div>

    <script type="module">
        import stateMajor from '/js/components/with-vue/majors/stateMajor.js';
        stateMajor()
    </script>
</x-layouts.app>
