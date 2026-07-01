@props([
    'title' => null,
    'description' => null,
    'createRoute' => null,
    'createLabel' => 'Tambah',
    'paginator' => null,
    'perPage' => 10,
    'searchable' => true,
    'livewire' => false,
])

<div class="bg-white border border-gray-200 rounded-md shadow-sm overflow-hidden data-table-wrapper">
    <div
        class="{{ $title || $description ? 'p-4' : 'p-3' }} border-b border-gray-100 flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-3">

        <div
            class="data-table-title-container flex-1 min-w-0 flex flex-col {{ !isset($title) && !isset($description) ? 'hidden' : '' }}">
            @if ($title)
                <h2 class="text-sm font-semibold text-gray-800">{{ $title }}</h2>
            @endif
            @if ($description)
                <p class="text-[11.5px] text-gray-500 mt-0.5">{{ $description }}</p>
            @endif
        </div>

        <div class="data-table-right-container flex items-center justify-end gap-2 sm:gap-3 w-full sm:w-auto sm:ml-auto">
            @if (isset($headerActions))
                {{ $headerActions }}
            @endif

            @if ($searchable)
                @if ($livewire)
                    <div class="flex-1 sm:flex-none relative">
                        <input type="text" wire:model.live.debounce.300ms="search" name="search" id="search_input"
                            aria-label="Cari Data" placeholder="Cari..." autocomplete="off"
                            class="w-full sm:w-[180px] pl-8 pr-3 py-1.5 text-[12.5px] border border-gray-300 rounded-md focus:ring-1 focus:ring-hijau focus:border-hijau focus:outline-none transition-colors">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                @else
                    <form method="GET" action="{{ url()->current() }}" class="flex-1 sm:flex-none relative">
                        @foreach (request()->except(['search', 'page']) as $key => $value)
                            @if ($value !== null && $value !== '')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <input type="text" name="search" id="search_input" aria-label="Cari Data"
                            value="{{ request('search') }}" placeholder="Cari..." autocomplete="off"
                            class="w-full sm:w-[180px] pl-8 pr-3 py-1.5 text-[12.5px] border border-gray-300 rounded-md focus:ring-1 focus:ring-hijau focus:border-hijau focus:outline-none transition-colors">
                        <button type="submit" class="hidden">Submit</button>
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </form>
                @endif
            @endif

            @if ($createRoute)
                <a href="{{ $createRoute }}"
                    class="px-3.5 py-1.5 bg-hijau hover:bg-hijau-dark text-white text-[12.5px] font-medium rounded-md transition-colors flex items-center justify-center gap-1.5 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ $createLabel }}
                </a>
            @endif
        </div>
    </div>


    @if (isset($bulkActions))
        <div
            class="bulk-actions-container hidden bg-hijau/10 border-b border-gray-100 px-4 py-2.5 items-center justify-between gap-3 transition-all duration-200">
            <div class="flex items-center gap-1.5">
                <span class="text-[12px] sm:text-[13px] font-semibold text-gray-800 selected-count-text">0</span>
                <span class="text-[12px] sm:text-[13px] font-medium text-gray-700">baris dipilih</span>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                {{ $bulkActions }}
            </div>
        </div>
    @endif

    <div class="overflow-x-auto scrollbar-thin">
        <table class="w-full min-w-[600px] text-left text-[13px]">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-medium">
                <tr>
                    {{ $head }}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700 data-table-body transition-opacity duration-200">
                @if ($paginator && $paginator->isEmpty())
                    <tr>
                        <td colspan="100%" class="px-4 py-8 text-center text-[11px] font-medium text-gray-400">
                            {{ $empty ?? 'Belum ada data yang ditemukan.' }}
                        </td>
                    </tr>
                @else
                    {{ $body }}
                @endif
            </tbody>
        </table>
    </div>

    @if ($paginator)
        <div
            class="p-3 border-t border-gray-100 flex items-center justify-between data-table-pagination relative min-h-[56px]">

            <div class="text-[11px] sm:text-[12px] text-gray-500 hidden sm:block flex-1">
                Showing <span class="font-medium text-gray-700">{{ $paginator->firstItem() ?? 0 }}</span> to <span
                    class="font-medium text-gray-700">{{ $paginator->lastItem() ?? 0 }}</span> of <span
                    class="font-medium text-gray-700">{{ $paginator->total() }}</span> results
            </div>

            <div
                class="absolute left-1/2 -translate-x-1/2 sm:static sm:translate-x-0 sm:left-auto flex justify-center z-10 shrink-0">
                @if ($livewire)
                    <div
                        class="flex items-center border border-gray-300 rounded-md overflow-hidden bg-white shadow-sm m-0">
                        <label for="per_page"
                            class="hidden sm:block text-[11px] sm:text-[12px] text-gray-500 font-medium px-2.5 py-1.5 border-r border-gray-300 bg-gray-50/80 m-0 shrink-0">Per
                            page</label>
                        <select wire:model.live="perPage" name="per_page" id="per_page"
                            class="w-[60px] text-[11px] sm:text-[12px] text-gray-700 border-none py-1.5 pl-2 pr-6 focus:ring-0 focus:outline-none focus:border-transparent cursor-pointer bg-transparent m-0 font-medium shadow-none">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                @else
                    <form method="GET" action="{{ url()->current() }}" class="flex items-center m-0">
                        @foreach (request()->except(['per_page', 'page']) as $key => $value)
                            @if ($value !== null && trim($value) !== '')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <div
                            class="flex items-center border border-gray-300 rounded-md overflow-hidden bg-white shadow-sm">
                            <label for="per_page"
                                class="hidden sm:block text-[11px] sm:text-[12px] text-gray-500 font-medium px-2.5 py-1.5 border-r border-gray-300 bg-gray-50/80 m-0 shrink-0">Per
                                page</label>
                            <select name="per_page" id="per_page" onchange="this.form.submit()"
                                class="w-[60px] text-[11px] sm:text-[12px] text-gray-700 border-none py-1.5 pl-2 pr-6 focus:ring-0 focus:outline-none focus:border-transparent cursor-pointer bg-transparent m-0 font-medium shadow-none">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </form>
                @endif
            </div>

            <div class="w-full sm:flex-1 flex sm:justify-end">
                @if ($paginator->hasPages())
                    <div class="w-full sm:w-auto overflow-x-auto shrink-0 flex items-center">
                        @php
                            $cleanQuery = collect(request()->query())
                                ->filter(function ($val, $key) {
                                    return $val !== null && trim($val) !== '' && !($key === 'per_page' && $val == 10);
                                })
                                ->toArray();
                        @endphp
                        {{ $paginator->appends($cleanQuery)->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
