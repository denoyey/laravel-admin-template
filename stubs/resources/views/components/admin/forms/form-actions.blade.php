@props([
    'submitLabel' => 'Simpan',
    'cancelRoute' => '#',
    'cancelLabel' => 'Batal',
])

<div class="mt-6 sm:mt-8 flex items-center justify-end gap-2 sm:gap-3 pt-4 sm:pt-5 border-t border-gray-100">
    <a href="{{ $cancelRoute }}"
        class="px-3 sm:px-4 py-1.5 sm:py-2 text-[12px] sm:text-[13px] font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors">
        {{ $cancelLabel }}
    </a>
    <button type="submit"
        class="px-3 sm:px-4 py-1.5 sm:py-2 text-[12px] sm:text-[13px] font-medium text-white bg-hijau hover:bg-hijau-dark rounded-md transition-colors shadow-sm">
        {{ $submitLabel }}
    </button>
</div>
