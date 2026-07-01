@props([
    'id' => 'image',
    'name' => 'image',
    'label' => 'Gambar',
    'required' => false,
    'accept' => 'image/*',
    'value' => null,
    'helpText' => 'Format: JPG, JPEG, PNG, WEBP. Maks: 2MB.',
    'previewClass' => 'w-full max-w-sm h-32 sm:h-auto sm:aspect-video',
])

<div>
    <label for="{{ $id }}" class="block text-[12px] sm:text-[13px] font-medium text-gray-700 mb-1">
        {{ $label }} @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    <div class="image-preview-wrapper" data-target="{{ $id }}-preview">

        <div
            class="border border-gray-200 rounded-md bg-white mb-4 shadow-sm flex flex-col sm:flex-row items-stretch overflow-hidden">
            <div class="flex-1 p-2.5 sm:p-3 flex flex-col justify-center">
                <input type="file" id="{{ $id }}" name="{{ $name }}" accept="{{ $accept }}"
                    {{ $required && !$value ? 'required' : '' }}
                    class="w-full text-[11px] sm:text-[13px] text-gray-600 file:cursor-pointer file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[11px] file:font-medium file:bg-hijau/10 file:text-hijau hover:file:bg-hijau/20 transition-colors focus:outline-none bg-transparent">

                @error($name)
                    <p class="mt-1.5 text-[11px] text-red-500 font-medium px-1">{{ $message }}</p>
                @enderror

                @if ($helpText)
                    <p class="mt-1.5 text-[10px] sm:text-[11px] text-gray-500 px-1">{{ $helpText }}</p>
                @endif
            </div>

            <div
                class="bg-gray-50/80 border-t sm:border-t-0 sm:border-l border-gray-200 p-2.5 sm:p-3 flex items-center justify-between sm:justify-center gap-4 shrink-0 sm:min-w-[200px]">
                <div class="flex flex-col">
                    <label for="compression_{{ $id }}"
                        class="text-[11px] font-semibold text-gray-700">Kualitas WebP</label>
                    <span class="text-[9px] sm:text-[10px] text-gray-500">Auto-convert (1-100)</span>
                </div>
                <div class="relative">
                    <input type="number" id="compression_{{ $id }}" name="compression_{{ $name }}"
                        value="80" min="1" max="100"
                        class="w-16 pl-2 pr-5 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-hijau/20 focus:border-hijau outline-none transition-colors text-gray-700 text-[12px] sm:text-[13px] bg-white text-center font-semibold shadow-sm">
                    <span
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-medium pointer-events-none">%</span>
                </div>
            </div>
        </div>

        <div id="{{ $id }}-preview" class="{{ $value ? 'block' : 'hidden' }}">
            <p class="text-[12px] font-medium text-gray-600 mb-2">Pratinjau Gambar:</p>
            <div
                class="relative rounded-md border border-gray-200 overflow-hidden bg-gray-50 flex items-center justify-center {{ $previewClass }} group">
                <img src="{{ $value ? asset('storage/' . $value) : '' }}"
                    data-original-src="{{ $value ? asset('storage/' . $value) : '' }}" alt="Preview"
                    class="preview-img object-contain w-full h-full {{ $value ? 'block' : 'hidden' }}">

                <button type="button"
                    class="btn-remove-image edit-overlay absolute top-2 left-2 p-1 sm:p-1.5 bg-white/90 hover:bg-white text-black rounded-full shadow-md transition-colors items-center justify-center {{ $value ? 'flex' : 'hidden' }}"
                    title="Hapus Gambar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="w-3 h-3 sm:w-4 sm:h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>


                <button type="button"
                    class="btn-crop-image edit-overlay absolute top-2 right-2 p-1 sm:p-1.5 bg-white/90 hover:bg-white text-gray-600 rounded-full shadow-md transition-colors items-center justify-center {{ $value ? 'flex' : 'hidden' }}"
                    title="Edit Gambar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-3 h-3 sm:w-4 sm:h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                    </svg>
                </button>

                <div
                    class="preview-placeholder flex flex-col items-center justify-center text-gray-400 w-full h-full {{ $value ? 'hidden' : 'flex' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 sm:w-8 sm:h-8 mb-1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <span class="text-[10px] sm:text-[11px]">Belum ada gambar</span>
                </div>
            </div>
        </div>
    </div>
</div>
