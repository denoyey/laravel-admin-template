<div id="cropper-modal"
    class="opacity-0 invisible fixed inset-0 flex items-center z-9999 justify-center pointer-events-none [&.is-open]:pointer-events-auto">

    <div id="cropper-modal-backdrop" class="absolute inset-0 bg-black/80 cursor-pointer"></div>

    <div class="relative bg-white rounded-md shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col m-4">


        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Edit Gambar</h3>
        </div>


        <div
            class="p-4 bg-gray-50 flex-1 overflow-hidden flex items-center justify-center min-h-[300px] lg:min-h-[500px]">
            <div class="w-full h-full max-h-[60vh] flex items-center justify-center">
                <img id="cropper-image" src="" alt="Cropper" width="800" height="600" class="max-w-full max-h-full block" draggable="false">
            </div>
        </div>


        <div
            class="px-6 py-4 border-t border-gray-100 bg-white rounded-b-md flex flex-col sm:flex-row items-center justify-between gap-4">


            <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-md">
                <button type="button"
                    class="cropper-tool-btn p-2 text-gray-600 hover:bg-white hover:text-hijau rounded-md transition-all shadow-sm"
                    data-action="zoom-in" title="Zoom In">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607ZM10.5 7.5v6m3-3h-6" />
                    </svg>
                </button>
                <button type="button"
                    class="cropper-tool-btn p-2 text-gray-600 hover:bg-white hover:text-hijau rounded-md transition-all shadow-sm"
                    data-action="zoom-out" title="Zoom Out">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607ZM13.5 10.5h-6" />
                    </svg>
                </button>
                <div class="w-px h-6 bg-gray-300 mx-1"></div>
                <button type="button"
                    class="cropper-tool-btn p-2 text-gray-600 hover:bg-white hover:text-hijau rounded-md transition-all shadow-sm"
                    data-action="rotate-left" title="Rotate Left">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                </button>
                <button type="button"
                    class="cropper-tool-btn p-2 text-gray-600 hover:bg-white hover:text-hijau rounded-md transition-all shadow-sm"
                    data-action="rotate-right" title="Rotate Right">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3" />
                    </svg>
                </button>
                <div class="w-px h-6 bg-gray-300 mx-1"></div>
                <button type="button"
                    class="cropper-tool-btn p-2 text-gray-600 hover:bg-white hover:text-hijau rounded-md transition-all shadow-sm"
                    data-action="flip-horizontal" title="Flip Horizontal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                    </svg>
                </button>
                <button type="button"
                    class="cropper-tool-btn p-2 text-gray-600 hover:bg-white hover:text-hijau rounded-md transition-all shadow-sm"
                    data-action="flip-vertical" title="Flip Vertical">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5" style="transform: rotate(90deg)">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                    </svg>
                </button>
            </div>


            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="button" id="btn-cancel-cropper"
                    class="flex-1 sm:flex-none px-4 py-2 bg-white border border-gray-300 text-gray-700 text-[13px] font-semibold rounded-md hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="button" id="btn-save-cropper"
                    class="flex-1 sm:flex-none px-4 py-2 bg-hijau border border-transparent text-white text-[13px] font-semibold rounded-md hover:bg-hijau-dark transition-colors shadow-sm">
                    Terapkan Potongan
                </button>
            </div>
        </div>

    </div>
</div>
