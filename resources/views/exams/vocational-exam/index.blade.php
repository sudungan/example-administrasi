<x-layouts.app :title="__('Ujian Keahlian')" appName="Example Administrasi">
    <div wire:ignore class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl">
            @include('partials.voc-exam-heading')
            <div id="app"></div>
        </div>
    </div>
    <script type="module">
        import stateVocationalExam from '/js/components/with-vue/voc-exam/stateVocationalExam.js'
        stateVocationalExam()
    </script>
</x-layouts.app>
