@can('view_any_user')
    <a href="{{ route('admin.users.index') }}"
        class="flex-1 lg:flex-none justify-center lg:justify-start px-2 sm:px-3 py-1.5 sm:py-2 rounded-md text-[11.5px] sm:text-[13px] font-medium transition-colors flex items-center gap-1.5 sm:gap-2.5 {{ request()->routeIs('admin.users.*') ? 'bg-hijau/10 text-hijau' : 'text-gray-600 hover:bg-gray-50' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-[15px] sm:h-[15px] shrink-0" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <span class="whitespace-nowrap">Pengguna</span>
    </a>
@endcan

@can('view_any_role')
    <a href="{{ route('admin.roles.index') }}"
        class="flex-1 lg:flex-none justify-center lg:justify-start px-2 sm:px-3 py-1.5 sm:py-2 rounded-md text-[11.5px] sm:text-[13px] font-medium transition-colors flex items-center gap-1.5 sm:gap-2.5 {{ request()->routeIs('admin.roles.*') ? 'bg-hijau/10 text-hijau' : 'text-gray-600 hover:bg-gray-50' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-[15px] sm:h-[15px] shrink-0" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        <span class="whitespace-nowrap">Role</span>
    </a>
@endcan
