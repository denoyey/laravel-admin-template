<div>
    <x-admin.tables.data-table :create-route="route('admin.file-upload-examples.create')" :paginator="$examples" :per-page="$perPage" :livewire="true"
        :searchable="true">
        <x-slot:bulkActions>
            <form action="{{ route('admin.file-upload-examples.bulk-delete') }}" method="POST" id="bulk-delete-form"
                class="m-0 no-protector">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="flex items-center gap-1.5 px-3 py-1.5 text-[12.5px] font-medium text-white hover:text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors cursor-pointer shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Terpilih
                </button>
            </form>
        </x-slot:bulkActions>

        <x-slot:head>
            <th class="px-4 py-3 w-[40px] text-center whitespace-nowrap"><input type="checkbox"
                    aria-label="Pilih semua baris"
                    class="select-all rounded border-gray-300 text-hijau focus:ring-hijau focus:ring-offset-0 cursor-pointer transition-colors w-4 h-4">
            </th>
            <th class="px-4 py-3 whitespace-nowrap">Cover</th>
            <th class="px-4 py-3 whitespace-nowrap">Galeri</th>
            <th class="px-4 py-3 whitespace-nowrap">Info File</th>
            <th class="px-4 py-3 text-right whitespace-nowrap">Aksi</th>
        </x-slot:head>
        <x-slot:body>
            @foreach ($examples as $p)
                <tr class="hover:bg-gray-50/50 transition-colors cursor-pointer"
                    onclick="window.location.href='{{ route('admin.file-upload-examples.edit', $p) }}'">
                    <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
                        <input type="checkbox" aria-label="Pilih baris"
                            class="row-checkbox rounded border-gray-300 text-hijau focus:ring-hijau focus:ring-offset-0 cursor-pointer transition-colors w-4 h-4"
                            value="{{ $p->id_file_upload }}">
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if ($p->cover_image)
                            <div class="w-10 h-10 rounded-md overflow-hidden bg-gray-100 shadow-sm">
                                <img src="{{ asset('storage/' . $p->cover_image) }}" alt="Cover" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-md bg-gray-50 border border-gray-200 text-gray-400 text-[10px] font-medium flex items-center justify-center shadow-sm">
                                N/A
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @php
                            $images = $p->images;
                            $totalImages = $images->count();
                            $displayLimit = 3;
                        @endphp

                        @if ($totalImages > 0)
                            <div class="flex -space-x-3">
                                @foreach ($images->take($displayLimit) as $img)
                                    <div
                                        class="w-10 h-10 rounded-full border-2 border-white overflow-hidden bg-gray-100 shadow-sm relative z-10">
                                        <img src="{{ asset('storage/' . $img->image_path) }}"
                                            alt="{{ $img->alt_text ?? 'Image' }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach

                                @if ($totalImages > $displayLimit)
                                    <div
                                        class="w-10 h-10 rounded-full border-2 border-white bg-gray-100 text-gray-600 text-[11px] font-bold flex items-center justify-center shadow-sm relative z-10">
                                        +{{ $totalImages - $displayLimit }}
                                    </div>
                                @endif
                            </div>
                        @else
                            <div
                                class="w-10 h-10 rounded-full border-2 border-white bg-gray-50 text-gray-400 text-[10px] font-medium flex items-center justify-center shadow-sm">
                                N/A
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900 mb-0.5 truncate max-w-[200px] sm:max-w-xs"
                            title="{{ $p->judul }}">{{ $p->judul }}</div>
                        <div class="truncate max-w-[200px] sm:max-w-xs text-[12.5px] text-gray-500 mb-1"
                            title="{{ strip_tags($p->deskripsi) }}">
                            {{ Str::limit(strip_tags($p->deskripsi), 80) }}
                        </div>
                    </td>
                    <td class="px-4 py-3 text-right whitespace-nowrap" onclick="event.stopPropagation()">
                        <x-admin.ui.action-buttons :edit-route="route('admin.file-upload-examples.edit', $p)" :delete-route="route('admin.file-upload-examples.destroy', $p)"
                            delete-message="Yakin ingin menghapus data ini?" />
                    </td>
                </tr>
            @endforeach
        </x-slot:body>
        <x-slot:empty>
            Belum ada data unggahan.
        </x-slot:empty>
    </x-admin.tables.data-table>
</div>
