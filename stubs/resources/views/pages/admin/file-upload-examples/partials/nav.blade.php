<a href="{{ route('admin.file-upload-examples.index') }}"
    class="flex-1 lg:flex-none justify-center lg:justify-start px-2 sm:px-3 py-1.5 sm:py-2 rounded-md text-[11.5px] sm:text-[13px] font-medium transition-colors flex items-center gap-1.5 sm:gap-2.5 {{ request()->routeIs('admin.file-upload-examples.*') ? 'bg-hijau/10 text-hijau' : 'text-gray-600 hover:bg-gray-50' }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-[15px] sm:h-[15px] shrink-0" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
    </svg>
    <span class="whitespace-nowrap">File Upload Demo</span>
</a>

<a href="{{ route('admin.multi-upload-examples.index') }}"
    class="flex-1 lg:flex-none justify-center lg:justify-start px-2 sm:px-3 py-1.5 sm:py-2 rounded-md text-[11.5px] sm:text-[13px] font-medium transition-colors flex items-center gap-1.5 sm:gap-2.5 {{ request()->routeIs('admin.multi-upload-examples.*') ? 'bg-hijau/10 text-hijau' : 'text-gray-600 hover:bg-gray-50' }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-[15px] sm:h-[15px] shrink-0" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
    </svg>
    <span class="whitespace-nowrap">Multi Image Gallery</span>
</a>
