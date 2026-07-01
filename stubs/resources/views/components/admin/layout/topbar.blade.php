<header id="admin-topbar"
    class="fixed top-0 right-0 left-0 lg:left-[240px] lg:in-[.sidebar-collapsed]:left-[64px] h-[60px] bg-white border-b border-gray-200 z-20
           flex items-center justify-between px-4 gap-3 sm:gap-4
           transition-all duration-300 ease-in-out">

    <div class="flex items-center gap-2 sm:gap-4 flex-1">
        <div class="flex items-center shrink-0">
            <button id="sidebar-toggle" aria-label="Toggle Sidebar"
                class="hidden lg:flex items-center justify-center w-8 h-8 rounded-md text-gray-500 bg-gray-100 hover:text-hijau transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M5 5H13V19H5V5ZM19 19H15V5H19V19ZM4 3C3.44772 3 3 3.44772 3 4V20C3 20.5523 3.44772 21 4 21H20C20.5523 21 21 20.5523 21 20V4C21 3.44772 20.5523 3 20 3H4ZM11 12L7 8.5V15.5L11 12Z">
                    </path>
                </svg>
            </button>

            <button id="sidebar-mobile-open" aria-label="Open Sidebar"
                class="flex lg:hidden items-center justify-center w-8 h-8 rounded-md text-gray-500 bg-gray-100 hover:text-hijau transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M5 5H13V19H5V5ZM19 19H15V5H19V19ZM4 3C3.44772 3 3 3.44772 3 4V20C3 20.5523 3.44772 21 4 21H20C20.5523 21 21 20.5523 21 20V4C21 3.44772 20.5523 3 20 3H4ZM11 12L7 8.5V15.5L11 12Z">
                    </path>
                </svg>
            </button>
        </div>

        <button type="button" id="global-search-trigger"
            class="group/search relative z-50 flex items-center bg-gray-100/80 hover:bg-gray-100 border border-transparent focus-within:bg-white focus-within:border-hijau/40 focus-within:ring-4 focus-within:ring-hijau/10 rounded-md px-2.5 sm:px-3 py-1.5 w-full md:w-64 lg:w-72 transition-all duration-200 outline-none text-left">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-[13px] h-[13px] text-gray-400 shrink-0 group-hover/search:text-gray-500 transition-colors"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span
                class="text-[11px] text-gray-400 w-full min-w-0 ml-2 group-hover/search:text-gray-500 transition-colors">Search...</span>
            <div
                class="hidden sm:flex items-center justify-center bg-white border border-gray-200 rounded px-1.5 py-0.5 text-[9px] font-semibold text-gray-500 ml-2 shadow-sm shrink-0">
                ⌘K
            </div>
        </button>
    </div>

    <div class="flex items-center gap-3">

        <div class="relative" id="topbar-notification-menu">
            <button id="notification-dropdown-btn"
                class="relative z-9999 flex items-center justify-center w-[34px] h-[34px] rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-hijau focus:bg-gray-200 focus:text-hijau transition-colors">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M22 20H2V18H3V11.0314C3 6.04348 7.02944 2 12 2C16.9706 2 21 6.04348 21 11.0314V18H22V20ZM5 18H19V11.0314C19 7.14806 15.866 4 12 4C8.13401 4 5 7.14806 5 11.0314V18ZM9.5 21H14.5C14.5 22.3807 13.3807 23.5 12 23.5C10.6193 23.5 9.5 22.3807 9.5 21Z">
                    </path>
                </svg>
                <span
                    class="absolute -top-0.5 -right-0.5 w-4 h-4 flex items-center justify-center text-[9px] font-bold text-white bg-red-500 border-2 border-white rounded-full">2</span>
            </button>

            <div id="notification-backdrop" class="hidden fixed inset-0 bg-transparent z-9998 transition-opacity">
            </div>

            <div id="notification-dropdown-menu"
                class="hidden fixed sm:absolute top-[65px] sm:top-full right-4 sm:right-0 left-4 sm:left-auto sm:mt-1.5 sm:w-80 bg-white rounded-md shadow-sm border border-gray-100 py-1.5 z-9999">
                <div class="px-3 py-2 border-b border-gray-100 flex items-center justify-between mb-1">
                    <span class="text-xs font-semibold text-gray-800">Notifikasi</span>
                    <div class="flex items-center gap-1">
                        <button type="button" title="Tandai semua dibaca"
                            class="p-1 text-gray-400 hover:text-hijau focus:text-hijau bg-gray-100 focus:bg-gray-200 rounded-md focus:outline-none transition-colors">
                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M11.602 13.7599L13.014 15.1719L21.4795 6.7063L22.8938 8.12051L13.014 18.0003L6.65 11.6363L8.06421 10.2221L10.189 12.3469L11.6025 13.7594L11.602 13.7599ZM11.6037 10.9322L16.0561 6.47982L17.4703 7.89403L13.018 12.3464L11.6037 10.9322ZM8.77698 16.5174L7.36277 17.9316L1 11.5689L2.41421 10.1547L3.82798 11.5685L8.77698 16.5174Z">
                                </path>
                            </svg>
                        </button>

                        <button type="button" title="Hapus semua"
                            class="p-1 text-gray-400 hover:text-red-600 focus:text-red-600 bg-gray-100 focus:bg-gray-200 rounded-md focus:outline-none transition-colors">
                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M4 8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8ZM6 10V20H18V10H6ZM9 12H11V18H9V12ZM13 12H15V18H13V12ZM7 5V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V5H22V7H2V5H7ZM9 4V5H15V4H9Z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="max-h-60 overflow-y-auto">
                    <div
                        class="px-3 py-2.5 border-b border-gray-50 bg-blue-50/10 hover:bg-gray-50 transition-colors flex items-start justify-between gap-2">
                        <div class="cursor-pointer flex-1">
                            <p class="text-[11px] text-gray-900 font-semibold leading-snug">Sistem Backup berhasil
                                diselesaikan.</p>
                            <p class="text-[9px] text-gray-500 mt-1">10 menit yang lalu</p>
                        </div>
                        <button type="button" title="Hapus pesan ini"
                            class="p-1 -mr-1 text-gray-400 hover:text-red-500 focus:text-red-500 bg-red-50 focus:bg-red-100 rounded-full focus:outline-none transition-colors shrink-0">
                            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6L6 18M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div
                        class="px-3 py-2.5 border-b border-gray-50 bg-white hover:bg-gray-50 transition-colors flex items-start justify-between gap-2">
                        <div class="cursor-pointer flex-1">
                            <p class="text-[11px] text-gray-500 leading-snug">Ada 1 pesan baru dari halaman kontak.</p>
                            <p class="text-[9px] text-gray-400 mt-1">1 jam yang lalu</p>
                        </div>
                        <button type="button" title="Hapus pesan ini"
                            class="p-1 -mr-1 text-gray-400 hover:text-red-500 focus:text-red-500 bg-red-50 focus:bg-red-100 rounded-full focus:outline-none transition-colors shrink-0">
                            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6L6 18M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="px-3 py-1.5 border-t border-gray-100 text-center mt-1">
                    <a href="#" class="text-[10px] font-medium text-hijau hover:underline">View All
                        Notifications</a>
                </div>
            </div>
        </div>

        <div class="relative" id="topbar-user-menu">
            <button id="user-dropdown-btn"
                class="relative z-9999 flex items-center justify-center w-8 h-8 rounded-full bg-hijau text-white hover:ring-2 hover:ring-gray-200 hover:ring-offset-1 focus:ring-2 focus:ring-gray-200 focus:ring-offset-1 transition-all">
                <span class="text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) }}
                </span>
            </button>

            <div id="user-backdrop" class="hidden fixed inset-0 bg-transparent z-9998 transition-opacity"></div>

            <div id="user-dropdown-menu"
                class="hidden absolute right-0 top-full mt-1.5 w-48 bg-white rounded-md shadow-sm border border-gray-100 py-1.5 z-9999">
                <div class="flex items-center gap-2.5 px-3 pb-2.5 mb-1.5 border-b border-gray-100">
                    <div
                        class="user-display-initial w-8 h-8 rounded-full bg-hijau flex items-center justify-center text-white text-xs font-bold shrink-0">
                        {{ strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex flex-col leading-tight min-w-0 flex-1">
                        <span
                            class="user-display-name text-[11px] sm:text-xs font-semibold text-gray-800 truncate" title="{{ auth()->user()->name ?? (auth()->user()->username ?? 'Admin') }}">{{ auth()->user()->name ?? (auth()->user()->username ?? 'Admin') }}</span>
                        <span
                            class="user-display-email text-[9px] font-medium text-hijau border border-hijau/30 bg-hijau/5 rounded px-1.5 py-0.5 mt-0.5 truncate max-w-full w-fit" title="{{ auth()->user()->email ?? 'admin@gmail.com' }}">{{ auth()->user()->email ?? 'admin@gmail.com' }}</span>
                    </div>
                </div>

                <div class="py-0.5">
                    <a href="{{ route('admin.profile.index') }}"
                        class="block px-3 py-1.5 text-[11px] sm:text-xs text-gray-700 hover:bg-gray-50 transition-colors">
                        Account Settings
                    </a>
                </div>

                <div class="border-t border-gray-100 mt-1.5 pt-0.5">
                    <button type="button"
                        class="trigger-logout w-full flex items-center gap-2 px-3 py-1.5 text-[11px] sm:text-xs text-red-500 hover:bg-red-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-[14px] h-[14px] shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </div>
            </div>
        </div>

</header>
