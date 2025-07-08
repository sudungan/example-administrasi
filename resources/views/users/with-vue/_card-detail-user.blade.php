<div class="w-full max-w-3xl p-2 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-4 dark:bg-gray-800 dark:border-gray-700">
    <div class="flex items-center justify-between mb-2">
        <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-gray-300">User Detail</h5>
        <button
            data-tooltip-target="tooltip-right" data-tooltip-placement="right"
            type="button"
            @click="backToPreviousPage"
            class="end-2.5 text-gray-400 bg-transparent dark:hover:bg-gray-600 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>

            <span class="sr-only">Close modal</span>
            <div id="tooltip-right" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
               Kembali
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </button>
    </div>
    {{-- USER DETAIL GENERAL --}}
    <div id="accordion-collapse" data-accordion="collapse">
        <h2 id="accordion-collapse-heading-1">
            <button
                @click="btnShowDataGeneral"
                type="button"
                class="cursor-pointer flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3">
                <span class="dark:text-gray-400 text-gray-600">Detail General</span>
                    <svg :class="{'rotate-180': cardUserDetail.general, 'rotate-0': !cardUserDetail.general}" class="w-3 h-3 transition-transform shrink-0" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                </svg>
            </button>
        </h2>
        <div v-show="cardUserDetail.general" class="transition-all duration-300 overflow-hidden">
            <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-800 bg-white">
                @include('users.with-vue._card-detail-user-general')
            </div>
        </div>
    </div>

     {{-- USER DETAIL PROFILE --}}
    <div id="accordion-collapse" data-accordion="collapse">
        <h2 id="accordion-collapse-heading-3">
            <button
                @click="btnShowDetailProfile"
                type="button"
                class="cursor-pointer flex items-center justify-between w-full p-5 rounded-b-xl font-medium rtl:text-right text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3">
            <span class="dark:text-gray-400 text-gray-600">Detail Profile </span>
            <svg :class="{'rotate-180': cardUserDetail.profile, 'rotate-0': ! cardUserDetail.profile}" class="w-3 h-3 transition-transform shrink-0" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
            </svg>
            </button>
        </h2>
        <div v-show="cardUserDetail.profile" class="transition-all duration-300 overflow-hidden">
            <div class="p-5 border border-t-0 border-gray-200 dark:border-gray-700 rounded-b-xl dark:bg-gray-800 bg-white">
            @include('users.with-vue._card-detail-user-profile')
            </div>
        </div>
    </div>
</div>
