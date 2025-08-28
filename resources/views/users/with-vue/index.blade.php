<x-layouts.app :title="__('Pengguna')" appName="Example Administrasi">
    <div wire:ignore class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl">
            @include('partials.users-heading')
            <div id="app"></div>
        </div>
    </div>
    <script type="module">
        import stateUserApp from '/js/components/with-vue/users/stateUserApp.js'
        stateUserApp()
    </script>
</x-layouts.app>
