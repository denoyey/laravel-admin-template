@props([
    'title',
    'value' => 0,
    'icon' => null,
    'url' => '#',
    'colorClass' => 'text-hijau-dark',
    'bgIconClass' => 'bg-hijau/10',
])

<a href="{{ $url }}"
    {{ $attributes->merge(['class' => 'block bg-white rounded-lg border border-gray-200 p-4 sm:p-5 transition-colors hover:border-hijau shrink-0 snap-start w-full']) }}>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-[11px] sm:text-[13px] font-medium text-gray-500">{{ $title }}</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-1 stat-counter" data-target="{{ $value }}">
                {{ number_format($value) }}</p>
        </div>
        @if ($icon)
            <div
                class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full shrink-0 {{ $bgIconClass }} {{ $colorClass }}">
                {!! $icon !!}
            </div>
        @endif
    </div>
</a>
