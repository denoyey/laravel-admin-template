<div id="idle-modal"
    class="opacity-0 invisible fixed inset-0 flex items-center z-9999 justify-center pointer-events-none [&.is-open]:pointer-events-auto px-4">

    <div id="idle-modal-backdrop" class="absolute inset-0 bg-black/60"></div>

    <div
        class="relative bg-white rounded-md shadow-sm w-full max-w-[380px] p-4 mb-20 md:mb-32 text-left transform scale-95 transition-transform duration-300 [&.is-open]:scale-100">
        <h3 class="text-[13px] md:text-base font-bold text-gray-900 mb-1.5 md:mb-2">Apakah Anda masih di sana?</h3>
        <p class="text-[11px] md:text-[13px] text-gray-500 mb-5 md:mb-6 leading-relaxed">
            Sistem mendeteksi tidak ada aktivitas. Sesi Anda akan otomatis diakhiri.
        </p>

        <div class="flex items-center gap-2">
            <button id="btn-keep-alive" type="button"
                class="px-3 py-1.5 md:px-4 md:py-2 bg-blue-500 border border-transparent text-white text-[11px] md:text-[13px] font-semibold rounded-md hover:bg-blue-600 focus:outline-none transition-colors shadow-sm">
                Tetap Login
            </button>
            <form method="POST" action="{{ route('admin.logout') }}" id="form-idle-logout">
                @csrf
                <input type="hidden" name="is_idle" value="1">
                <button type="submit" id="btn-idle-logout"
                    class="px-3 py-1.5 md:px-4 md:py-2 bg-red-500/90 border border-transparent text-white text-[11px] md:text-[13px] font-semibold rounded-md hover:bg-red-600 focus:outline-none transition-colors shadow-sm">
                    Logout (60s)
                </button>
            </form>
        </div>
    </div>
</div>
