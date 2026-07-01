<div id="logout-modal"
    class="opacity-0 invisible fixed inset-0 flex items-center z-9999 justify-center p-4 pointer-events-none [&.is-open]:pointer-events-auto">

    <div id="logout-modal-backdrop" class="absolute inset-0 bg-black/50"></div>

    <div
        class="relative bg-white rounded-md shadow-2xl w-full max-w-sm p-6">
        <div class="flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1">Konfirmasi Logout</h3>
            <p class="text-[13px] text-gray-500 mb-6">Apakah Anda yakin ingin keluar dari aplikasi?</p>
        </div>

        <div class="flex items-center gap-2.5 w-full">
            <button id="btn-cancel-logout" type="button"
                class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-[13px] font-semibold rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-colors">
                Batal
            </button>
            <form method="POST" action="{{ route('admin.logout') }}" class="flex-1" id="form-logout-admin">
                @csrf
                <button type="submit"
                    class="w-full px-4 py-2 bg-red-600 border border-transparent text-white text-[13px] font-semibold rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    Ya, Logout
                </button>
            </form>
        </div>
    </div>
</div>
