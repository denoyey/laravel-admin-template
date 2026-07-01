<div id="toast-container"
    class="fixed top-6 left-1/2 -translate-x-1/2 z-100 flex flex-col gap-3 pointer-events-none items-center">
    @if (session('success'))
        <div
            class="toast-alert bg-white border border-hijau/20 shadow-[0_4px_12px_rgba(0,0,0,0.05)] rounded-md p-3.5 flex items-start gap-3 w-max min-w-[300px] max-w-md pointer-events-auto transform transition-all duration-300 opacity-0 scale-95">
            <div class="bg-hijau/10 p-1.5 rounded-full shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-hijau" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="text-[13px] font-semibold text-gray-800">Berhasil</h4>
                <p class="text-[12px] text-gray-500 mt-0.5 leading-snug">{{ session('success') }}</p>
            </div>
            <button type="button"
                class="toast-close text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-md hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div
            class="toast-alert bg-white border border-red-100 shadow-[0_4px_12px_rgba(0,0,0,0.05)] rounded-md p-3.5 flex items-start gap-3 w-max min-w-[300px] max-w-md pointer-events-auto transform transition-all duration-300 opacity-0 scale-95">
            <div class="bg-red-50 p-1.5 rounded-full shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="text-[13px] font-semibold text-gray-800">Gagal</h4>
                <p class="text-[12px] text-gray-500 mt-0.5 leading-snug">{{ session('error') }}</p>
            </div>
            <button type="button"
                class="toast-close text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-md hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        <div
            class="toast-alert bg-white border border-red-100 shadow-[0_4px_12px_rgba(0,0,0,0.05)] rounded-md p-3.5 flex items-start gap-3 w-max min-w-[300px] max-w-md pointer-events-auto transform transition-all duration-300 opacity-0 scale-95">
            <div class="bg-red-50 p-1.5 rounded-full shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="text-[13px] font-semibold text-gray-800">Kesalahan Validasi</h4>
                <ul class="list-disc pl-4 mt-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-[12px] text-gray-500 leading-snug">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button"
                class="toast-close text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-md hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif
</div>
