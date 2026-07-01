<div id="delete-modal"
    class="opacity-0 invisible fixed inset-0 flex items-center z-9999 justify-center p-4 pointer-events-none [&.is-open]:pointer-events-auto">

    <div id="delete-modal-backdrop" class="absolute inset-0 bg-black/50 cursor-pointer"></div>

    <div
        class="relative bg-white rounded-md shadow-2xl w-full max-w-sm p-6">
        <div class="flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1" id="delete-modal-title">Konfirmasi Hapus</h3>
            <p class="text-[13px] text-gray-500 mb-6" id="delete-modal-message">Apakah Anda yakin ingin menghapus data ini?</p>
        </div>

        <div class="flex items-center gap-2.5 w-full">
            <button id="btn-cancel-delete" type="button"
                class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-[13px] font-semibold rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-colors">
                Batal
            </button>
            <form method="POST" action="" class="flex-1" id="form-delete-modal">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full px-4 py-2 bg-red-600 border border-transparent text-white text-[13px] font-semibold rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>
