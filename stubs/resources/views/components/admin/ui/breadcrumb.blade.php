@props(['items' => []])

<div class="flex flex-wrap items-center gap-1.5 sm:gap-2 text-[11px] sm:text-[13px] text-gray-500 font-medium">
    @foreach ($items as $index => $item)
        @php
            $isLast = $index === count($items) - 1;
            $hasUrl = !empty($item['url']);
            $defaultClass = $isLast ? 'text-gray-800' : '';
            $class = isset($item['class']) ? $item['class'] : $defaultClass;
        @endphp

        @if ($hasUrl && !$isLast)
            <a href="{{ $item['url'] }}" class="hover:text-hijau transition-colors {{ $class }}">{{ $item['label'] }}</a>
        @else
            <span class="{{ $class }}">{{ $item['label'] }}</span>
        @endif

        @if (!$isLast)
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        @endif
    @endforeach
</div>
