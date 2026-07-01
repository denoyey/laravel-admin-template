<div id="global-search-modal" class="fixed inset-0 z-9999 hidden" role="dialog" aria-modal="true">

    <div id="global-search-backdrop" class="fixed inset-0 bg-black/60 transition-opacity"></div>


    <div id="global-search-wrapper" class="fixed inset-0 z-10 overflow-y-auto p-2 sm:p-6 md:p-20">
        <div id="global-search-panel"
            class="mx-auto max-w-2xl transform divide-y divide-gray-100 overflow-hidden rounded-md bg-white shadow-2xl transition-all opacity-0 scale-95 flex flex-col max-h-[95vh] sm:max-h-[80vh]">


            <div class="relative flex items-center px-3 py-2 sm:px-4 sm:py-3 shrink-0">
                <svg class="pointer-events-none absolute left-3 sm:left-4 h-4 w-4 sm:h-5 sm:w-5 text-gray-400" viewBox="0 0 20 20"
                    fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                        clip-rule="evenodd" />
                </svg>
                <input type="text" id="global-search-input"
                    class="h-10 sm:h-12 w-full bg-transparent pl-8 sm:pl-10 pr-10 sm:pr-12 text-[11px] sm:text-xs text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-0 border-0"
                    placeholder="Cari dokumentasi atau data..." role="combobox" aria-expanded="false"
                    aria-controls="options" autocomplete="off">
                <div class="absolute right-3 sm:right-4 flex items-center gap-2">
                    <kbd
                        class="hidden sm:inline-flex items-center justify-center rounded border border-gray-200 bg-gray-50 px-2 py-0.5 text-[10px] font-medium text-gray-400">esc</kbd>
                </div>
            </div>


            <div id="global-search-loading" class="hidden px-6 py-10 text-center shrink-0">
                <svg class="animate-spin mx-auto h-6 w-6 text-hijau" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <p class="mt-4 text-[11px] sm:text-xs text-gray-500">Mencari data...</p>
            </div>


            <div id="global-search-empty" class="px-6 py-16 text-center text-[11px] sm:text-xs sm:px-14 shrink-0">
                <p class="text-gray-500">Tidak ada pencarian terbaru</p>
            </div>


            <div id="global-search-no-results"
                class="hidden px-6 py-14 text-center text-[11px] sm:text-xs sm:px-14 shrink-0">
                <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="mt-4 font-semibold text-gray-900">Hasil tidak ditemukan</p>
                <p class="mt-2 text-gray-500">Tidak ada data yang cocok untuk "<span id="search-query-text"
                        class="font-medium text-gray-700"></span>".</p>
            </div>


            <ul id="global-search-results"
                class="hidden overflow-y-auto py-2 text-[11px] sm:text-xs text-gray-800 flex-1" role="listbox">

            </ul>

            <template id="global-search-result-template">
                <li class="cursor-pointer select-none px-4 py-1.5 hover:bg-transparent group transition-colors"
                    role="option">
                    <a href="#"
                        class="search-result-link flex items-center gap-3 w-full outline-none bg-gray-50 hover:bg-hijau/30 focus:bg-hijau/30 border border-gray-100 hover:border-hijau/40 focus:border-hijau/40 rounded-md px-3 py-2.5 transition-all shadow-sm">
                        <div
                            class="flex h-7 w-7 items-center justify-center rounded bg-white text-gray-400 group-hover:text-hijau group-focus:text-hijau border border-gray-100 group-hover:border-hijau/30 group-focus:border-hijau/30 shadow-sm shrink-0 search-result-icon-wrapper transition-colors">

                        </div>
                        <div class="flex-auto min-w-0">
                            <p
                                class="search-result-title text-[11px] sm:text-xs font-medium text-gray-700 truncate group-hover:text-gray-900 group-focus:text-gray-900 transition-colors">
                            </p>
                        </div>
                        <svg class="h-4 w-4 text-gray-400 group-hover:text-hijau group-focus:text-hijau transition-colors shrink-0"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </li>
            </template>


            <div class="hidden sm:flex shrink-0 items-center justify-end bg-gray-50 px-4 py-2 border-t border-gray-100">
                <span class="text-[10px] text-gray-400 font-medium">Navigasi menggunakan panah </span>
                <div class="ml-2 flex items-center gap-1 text-gray-400">
                    <kbd
                        class="font-sans flex items-center justify-center rounded border border-gray-200 bg-white px-1 py-0.5 text-[10px]">&uarr;</kbd>
                    <kbd
                        class="font-sans flex items-center justify-center rounded border border-gray-200 bg-white px-1 py-0.5 text-[10px]">&darr;</kbd>
                </div>
                <span class="ml-3 text-[10px] text-gray-400 font-medium">dan </span>
                <kbd
                    class="ml-1 font-sans flex items-center justify-center rounded border border-gray-200 bg-white px-1.5 py-0.5 text-[10px] text-gray-400">Enter</kbd>
            </div>
        </div>
    </div>
</div>
