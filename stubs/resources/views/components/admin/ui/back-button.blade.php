@props([
    'route' => '#',
    'label' => 'Kembali',
    'class' => 'mb-4',
])

<div class="{{ $class }}">
    <a href="{{ $route }}"
        class="inline-flex items-center gap-1.5 sm:gap-2 text-[12px] sm:text-[13px] text-gray-600 hover:text-gray-900 bg-white border border-gray-200 hover:border-gray-300 px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-md shadow-sm transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span class="font-medium">{{ $label }}</span>
    </a>
</div>
