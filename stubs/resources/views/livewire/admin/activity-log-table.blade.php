<div>
    <x-admin.tables.data-table :paginator="$activities" :per-page="$perPage" :livewire="true" :searchable="true">
        <x-slot:bulkActions>
            @can('delete_any_activity')
                <form action="{{ route('admin.activity-logs.bulk-delete') }}" method="POST" id="bulk-delete-form"
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
            @endcan
        </x-slot:bulkActions>

        <x-slot:head>
            @can('delete_any_activity')
                <th class="px-4 py-3 w-[40px] text-center whitespace-nowrap"><input type="checkbox"
                        class="select-all rounded border-gray-300 text-hijau focus:ring-hijau focus:ring-offset-0 cursor-pointer transition-colors w-4 h-4">
                </th>
            @endcan
            <th class="px-4 py-3 whitespace-nowrap">Waktu</th>
            <th class="px-4 py-3 whitespace-nowrap">Pengguna</th>
            <th class="px-4 py-3 whitespace-nowrap">Aksi</th>
            <th class="px-4 py-3 whitespace-nowrap">Deskripsi</th>
            <th class="px-4 py-3 whitespace-nowrap">Data ID</th>
            <th class="px-4 py-3 whitespace-nowrap text-right">Opsi</th>
        </x-slot:head>
        <x-slot:body>
            @foreach ($activities as $activity)
                <tr class="hover:bg-gray-50/50 transition-colors {{ auth()->user()->can('view_activity') ? 'cursor-pointer group' : '' }}"
                    @can('view_activity') onclick="window.location='{{ route('admin.activity-logs.show', $activity) }}'" @endcan>
                    @can('delete_any_activity')
                        <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
                            <input type="checkbox"
                                class="row-checkbox rounded border-gray-300 text-hijau focus:ring-hijau focus:ring-offset-0 cursor-pointer transition-colors w-4 h-4"
                                value="{{ $activity->id }}">
                        </td>
                    @endcan
                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap text-xs">
                        {{ $activity->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                        @if ($activity->causer)
                            {{ $activity->causer->username }}
                            <div class="text-[10px] text-gray-500 font-normal">{{ $activity->causer->email }}</div>
                        @else
                            <span class="text-gray-400 italic">Sistem</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @php
                            $badgeClass = match ($activity->event) {
                                'created' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'updated' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'deleted' => 'bg-red-100 text-red-700 border-red-200',
                                default => 'bg-gray-100 text-gray-700 border-gray-200',
                            };
                        @endphp
                        <span
                            class="px-2 py-0.5 rounded-full text-[11px] font-medium border uppercase tracking-wider {{ $badgeClass }}">
                            {{ $activity->event }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-[13px] max-w-[200px] sm:max-w-xs truncate"
                        title="{{ $activity->description }}">
                        {{ $activity->description }}
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-[12px] whitespace-nowrap font-mono">
                        @if ($activity->subject_id)
                            {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right whitespace-nowrap" onclick="event.stopPropagation()">
                        <x-admin.ui.action-buttons :view-route="auth()->user()->can('view_activity')
                            ? route('admin.activity-logs.show', $activity)
                            : null" :delete-route="auth()->user()->can('delete_activity')
                            ? route('admin.activity-logs.destroy', $activity)
                            : null"
                            delete-message="Yakin ingin menghapus riwayat aktivitas ini?" />
                    </td>
                </tr>
            @endforeach
        </x-slot:body>
        <x-slot:empty>
            Belum ada data aktivitas log.
        </x-slot:empty>
    </x-admin.tables.data-table>
</div>
