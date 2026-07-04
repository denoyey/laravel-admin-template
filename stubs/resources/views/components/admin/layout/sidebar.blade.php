<aside id="admin-sidebar"
    class="fixed top-0 left-0 h-full w-[240px] bg-white border-r border-gray-200 z-110 lg:z-40
           flex flex-col overflow-hidden whitespace-nowrap
           transition-all duration-300 ease-in-out
           -translate-x-full lg:translate-x-0 in-[.sidebar-open]:translate-x-0
           lg:in-[.sidebar-collapsed]:w-[64px]
           [.sidebar-collapsed_&_.sidebar-label]:hidden
           [.sidebar-collapsed_&_.section-toggle_.chevron-icon]:hidden
           [.sidebar-collapsed_&_.nav-item]:justify-center
           [.sidebar-collapsed_&_.nav-item]:px-0
           [.sidebar-collapsed_&_.logo-wrapper]:justify-center
           [.sidebar-collapsed_&_.logo-wrapper]:px-0
           lg:[.sidebar-collapsed_&_.section-content]:max-h-none!
           lg:[.sidebar-collapsed_&_.section-content]:opacity-100!
           lg:[.sidebar-collapsed_&_.section-content]:!visibility-visible
           lg:[.sidebar-collapsed_&_.section-toggle]:cursor-default">

    <div class="flex items-center px-3.5 h-[60px] border-b border-gray-100 shrink-0 logo-wrapper">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 overflow-hidden">
            <img src="{{ asset('src/img/logo-admin.svg') }}" alt="Logo Admin" class="w-7 h-7 object-contain shrink-0"
                loading="lazy">
            <div class="flex flex-col leading-none gap-[2px] sidebar-label">
                <span
                    class="text-[10.5px] text-hijau-dark font-bold uppercase whitespace-nowrap tracking-wide leading-tight">
                    Denoyey
                </span>
                <span class="text-[7px] text-black font-medium whitespace-nowrap leading-tight">
                    Admin Starter Kit
                </span>
            </div>
        </a>
    </div>

    <nav
        class="flex-1 overflow-y-auto overflow-x-hidden overscroll-contain py-3 px-3 scrollbar-thin hover:scrollbar-thumb-gray-300">


        <div class="sidebar-section mb-3" data-section="main">
            <button type="button"
                class="section-toggle w-full flex items-center justify-between px-2 pb-2 pt-1 text-[11px] font-semibold text-gray-600 uppercase tracking-widest hover:text-gray-800 transition-colors cursor-pointer group">
                <span class="sidebar-label">Main</span>
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-3.5 h-3.5 transform transition-transform duration-200 chevron-icon text-gray-600 group-hover:text-gray-800"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div class="section-content space-y-0.5 overflow-hidden transition-all duration-300 origin-top">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-item group flex items-center gap-3 px-3 py-2 rounded-md text-[13px] font-medium text-gray-700
                           hover:bg-hijau/10 hover:text-hijau transition-colors duration-150
                           {{ request()->routeIs('admin.dashboard') ? 'nav-active bg-hijau/10 text-hijau' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px] shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="sidebar-label">Dashboard</span>
                </a>

                <!-- File Upload Demo -->
                <a href="{{ route('admin.file-upload-examples.index') }}"
                    class="nav-item group flex items-center gap-3 px-3 py-2 rounded-md text-[13px] font-medium text-gray-700
                           hover:bg-hijau/10 hover:text-hijau transition-colors duration-150
                           {{ request()->routeIs('admin.file-upload-examples.*') ? 'nav-active bg-hijau/10 text-hijau' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px] shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span class="sidebar-label">File Upload Demo</span>
                </a>

                <!-- Multi Image Gallery -->
                <a href="{{ route('admin.multi-upload-examples.index') }}"
                    class="nav-item group flex items-center gap-3 px-3 py-2 rounded-md text-[13px] font-medium text-gray-700
                           hover:bg-hijau/10 hover:text-hijau transition-colors duration-150
                           {{ request()->routeIs('admin.multi-upload-examples.*') ? 'nav-active bg-hijau/10 text-hijau' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="sidebar-label">Multi Image Gallery</span>
                </a>
            </div>
        </div>


        @canany(['view_any_user', 'view_any_role', 'view_any_activity', 'manage_system_settings'])
            <div class="sidebar-section" data-section="system">
                <button type="button"
                    class="section-toggle w-full flex items-center justify-between px-2 pb-2 pt-1 text-[11px] font-semibold text-gray-600 uppercase tracking-widest hover:text-gray-800 transition-colors cursor-pointer group">
                    <span class="sidebar-label">System</span>
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-3.5 h-3.5 transform transition-transform duration-200 chevron-icon text-gray-500 group-hover:text-gray-700"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="section-content space-y-0.5 overflow-hidden transition-all duration-300 origin-top">
                    @canany(['view_any_user', 'view_any_role'])
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-item group flex items-center gap-3 px-3 py-2 rounded-md text-[13px] font-medium text-gray-600
                           hover:bg-hijau/10 hover:text-hijau transition-colors duration-150
                           {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') ? 'nav-active bg-hijau/10 text-hijau' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px] shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="sidebar-label">Access Management</span>
                        </a>
                    @endcanany

                    @can('view_any_activity')
                        <a href="{{ route('admin.activity-logs.index') }}"
                            class="nav-item group flex items-center gap-3 px-3 py-2 rounded-md text-[13px] font-medium text-gray-600
                           hover:bg-hijau/10 hover:text-hijau transition-colors duration-150
                           {{ request()->routeIs('admin.activity-logs.*') ? 'nav-active bg-hijau/10 text-hijau' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px] shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="sidebar-label">Activity Logs</span>
                        </a>
                    @endcan

                    @can('manage_system_settings')
                        <a href="{{ route('admin.profile.index') }}"
                            class="nav-item group flex items-center gap-3 px-3 py-2 rounded-md text-[13px] font-medium text-gray-600
                                   hover:bg-hijau/10 hover:text-hijau transition-colors duration-150
                                   {{ request()->routeIs('admin.profile.*') ? 'nav-active bg-hijau/10 text-hijau' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px] shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="sidebar-label">Setting</span>
                        </a>
                    @endcan
                </div>
            </div>
        @endcanany

    </nav>

    <div class="border-t border-gray-100 p-3 shrink-0">
        <button type="button"
            class="trigger-logout nav-item w-full flex items-center gap-3 px-3 py-2 rounded-md text-[13px] font-medium text-gray-500
                   hover:bg-red-50 hover:text-red-600 transition-colors duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px] shrink-0" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="sidebar-label">Logout</span>
        </button>
    </div>

</aside>
