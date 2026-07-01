<div>
    <x-admin.tables.data-table :create-route="auth()->user()->can('create_role') ? route('admin.roles.create') : null" :paginator="$roles" :per-page="$perPage" :livewire="true"
        :searchable="true">
        <x-slot:bulkActions>
            @can('delete_any_role')
                <form action="{{ route('admin.roles.bulk-delete') }}" method="POST" id="bulk-delete-form"
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
            <th class="px-4 py-3 w-[40px] text-center whitespace-nowrap"><input type="checkbox"
                    aria-label="Pilih semua baris"
                    class="select-all rounded border-gray-300 text-hijau focus:ring-hijau focus:ring-offset-0 cursor-pointer transition-colors w-4 h-4">
            </th>
            <th class="px-4 py-3 whitespace-nowrap">Nama Role</th>
            <th class="px-4 py-3 whitespace-nowrap">Jumlah User</th>
            <th class="px-4 py-3 whitespace-nowrap">Jumlah Permission</th>
            <th class="px-4 py-3 text-right whitespace-nowrap">Aksi</th>
        </x-slot:head>
        <x-slot:body>
            @foreach ($roles as $r)
                <tr class="hover:bg-gray-50/50 transition-colors {{ auth()->user()->can('update_role') || auth()->user()->can('view_role') ? 'cursor-pointer group' : '' }}"
                    @can('update_role') onclick="window.location.href='{{ route('admin.roles.edit', $r) }}'" 
                    @elsecan('view_role') onclick="window.location.href='{{ route('admin.roles.show', $r) }}'" @endcan>
                    <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
                        @if ($r->name === 'super_admin')
                            <input type="checkbox" disabled aria-label="Tidak dapat memilih baris ini"
                                class="rounded border-gray-200 text-gray-300 bg-gray-100 cursor-not-allowed w-4 h-4">
                        @else
                            <input type="checkbox" aria-label="Pilih baris ini"
                                class="row-checkbox rounded border-gray-300 text-hijau focus:ring-hijau focus:ring-offset-0 cursor-pointer transition-colors w-4 h-4"
                                value="{{ $r->id }}">
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900 capitalize whitespace-nowrap">{{ $r->name }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span
                            class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[11px] font-medium border border-blue-100">
                            {{ $r->users_count }} Users
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span
                            class="px-2 py-0.5 rounded-full bg-oren/10 text-oren-dark text-[11px] font-medium border border-oren/20">
                            {{ $r->permissions_count }} Permissions
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right whitespace-nowrap" onclick="event.stopPropagation()">
                        <x-admin.ui.action-buttons :view-route="auth()->user()->can('view_role') ? route('admin.roles.show', $r) : null" :edit-route="auth()->user()->can('update_role') ? route('admin.roles.edit', $r) : null" :delete-route="auth()->user()->can('delete_role') ? route('admin.roles.destroy', $r) : null"
                            delete-message="Yakin ingin menghapus role ini?" :can-delete="$r->name !== 'super_admin'"
                            cannot-delete-message="Role super_admin tidak dapat dihapus" />
                    </td>
                </tr>
            @endforeach
        </x-slot:body>
        <x-slot:empty>
            Belum ada data Role.
        </x-slot:empty>
    </x-admin.tables.data-table>
</div>
