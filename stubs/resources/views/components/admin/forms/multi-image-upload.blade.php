@props([
    'id' => 'images',
    'name' => 'images[]',
    'label' => 'Gambar Sub Service (Multi Upload)',
    'required' => false,
    'accept' => 'image/*',
    'helpText' => 'Pilih banyak gambar sekaligus. Format: JPG, JPEG, PNG, WEBP. Maks: 2MB per gambar.',
    'hideCover' => false,
])

<div class="mb-6" data-upload-id="{{ $id }}" data-hide-cover="{{ $hideCover ? 'true' : 'false' }}">
    <label for="{{ $id }}" class="block text-[12px] sm:text-[13px] font-medium text-gray-700 mb-1">
        {{ $label }} @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    <div
        class="border border-gray-200 rounded-md bg-white mb-5 shadow-sm flex flex-col sm:flex-row items-stretch overflow-hidden">
        <div class="flex-1 p-2.5 sm:p-3 flex flex-col justify-center">
            <input type="file" id="{{ $id }}" name="{{ $name }}" accept="{{ $accept }}"
                multiple {{ $required ? 'required' : '' }}
                class="w-full text-[11px] sm:text-[13px] text-gray-600 file:cursor-pointer file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[11px] file:font-medium file:bg-hijau/10 file:text-hijau hover:file:bg-hijau/20 transition-colors focus:outline-none bg-transparent">

            @error(str_replace('[]', '', $name))
                <p class="mt-1.5 text-[11px] text-red-500 font-medium px-1">{{ $message }}</p>
            @enderror
            @error(str_replace('[]', '', $name) . '.*')
                <p class="mt-1.5 text-[11px] text-red-500 font-medium px-1">{{ $message }}</p>
            @enderror

            @if ($helpText)
                <p class="mt-1.5 text-[10px] sm:text-[11px] text-gray-500 px-1">{{ $helpText }}</p>
            @endif
        </div>

        <div
            class="bg-gray-50/80 border-t sm:border-t-0 sm:border-l border-gray-200 p-2.5 sm:p-3 flex items-center justify-between sm:justify-center gap-4 shrink-0 sm:min-w-[200px]">
            <div class="flex flex-col">
                <label for="compression_{{ $id }}" class="text-[11px] font-semibold text-gray-700">Kualitas
                    WebP</label>
                <span class="text-[9px] sm:text-[10px] text-gray-500">Auto-convert (1-100)</span>
            </div>
            <div class="relative">
                <input type="number" id="compression_{{ $id }}"
                    name="compression_{{ str_replace('[]', '', $name) }}" value="80" min="1" max="100"
                    class="w-16 pl-2 pr-5 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-hijau/20 focus:border-hijau outline-none transition-colors text-gray-700 text-[12px] sm:text-[13px] bg-white text-center font-semibold shadow-sm">
                <span
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-medium pointer-events-none">%</span>
            </div>
        </div>
    </div>

    <div id="{{ $id }}-preview-container" class="hidden flex-col gap-3">
        <div class="flex items-center justify-between">
            <p class="text-[12px] font-medium text-gray-700">Pratinjau Gambar Baru:</p>
            <button type="button" id="{{ $id }}-clear-all"
                class="text-[11px] text-red-500 hover:text-red-700 font-medium bg-red-50 hover:bg-red-100 px-2 py-1 rounded transition-colors">
                Hapus Semua
            </button>
        </div>
        <div class="w-full overflow-x-auto pb-4 custom-scrollbar">
            <div id="{{ $id }}-preview-list" class="flex gap-4 min-w-max">

            </div>
        </div>
    </div>

    <input type="file" id="{{ $id }}-single-replacer" accept="{{ $accept }}" class="hidden">
</div>
