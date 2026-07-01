@props([
    'viewRoute' => null,
    'editRoute' => null,
    'deleteRoute' => null,
    'manageItemsRoute' => null,
    'deleteMessage' => 'Yakin ingin menghapus data ini?',
    'canDelete' => true,
    'cannotDeleteMessage' => 'Tidak dapat dihapus',
])

<div class="flex items-center justify-end gap-2">
    @if ($manageItemsRoute)
        <a href="{{ $manageItemsRoute }}" class="text-indigo-600 hover:text-indigo-800 p-1 bg-indigo-50 hover:bg-indigo-100 rounded transition-colors" title="Kelola Item / Sub-Service">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        </a>
    @endif
    @if ($viewRoute)
        <a href="{{ $viewRoute }}" class="text-teal-600 hover:text-teal-800 p-1 bg-teal-50 hover:bg-teal-100 rounded transition-colors" title="Lihat">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </a>
    @endif

    @if ($editRoute)
        <a href="{{ $editRoute }}" class="text-blue-600 hover:text-blue-800 p-1 bg-blue-50 hover:bg-blue-100 rounded transition-colors" title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
        </a>
    @endif

    @if ($deleteRoute)
        @if ($canDelete)
            <form action="{{ $deleteRoute }}" method="POST" class="inline-block form-delete-action no-protector" data-message="{{ $deleteMessage }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 p-1 bg-red-50 hover:bg-red-100 rounded transition-colors" title="Delete">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>
        @else
            <button type="button" disabled class="text-gray-400 p-1 bg-gray-100 rounded cursor-not-allowed" title="{{ $cannotDeleteMessage }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        @endif
    @endif
</div>
