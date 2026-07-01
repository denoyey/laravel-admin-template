<div>
    <x-admin.tables.data-table :create-route="auth()->user()->hasRole('super_admin') ? route('admin.users.create') : null" :paginator="$users" :per-page="$perPage" :livewire="true"
        :searchable="true">
        <x-slot:bulkActions>
            @role('super_admin')
                <form action="{{ route('admin.users.bulk-delete') }}" method="POST" id="bulk-delete-form"
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
            @endrole
        </x-slot:bulkActions>

        <x-slot:head>
            @role('super_admin')
                <th class="px-4 py-3 w-[40px] text-center whitespace-nowrap"><input type="checkbox"
                        aria-label="Pilih semua baris"
                        class="select-all rounded border-gray-300 text-hijau focus:ring-hijau focus:ring-offset-0 cursor-pointer transition-colors w-4 h-4">
                </th>
            @endrole
            <th class="px-4 py-3 whitespace-nowrap">Nama</th>
            <th class="px-4 py-3 whitespace-nowrap">Email</th>
            <th class="px-4 py-3 whitespace-nowrap">Role</th>
            <th class="px-4 py-3 text-right whitespace-nowrap">Aksi</th>
        </x-slot:head>
        <x-slot:body>
            @foreach ($users as $u)
                @php
                    $canEdit =
                        auth()->user()->hasRole('super_admin') ||
                        (auth()->user()->can('update_user') && $u->id_users === auth()->id());
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors {{ $canEdit || auth()->user()->can('view_user') ? 'cursor-pointer group' : '' }}"
                    @if ($canEdit) onclick="window.location.href='{{ route('admin.users.edit', $u) }}'" 
                    @elseif(auth()->user()->can('view_user')) onclick="window.location.href='{{ route('admin.users.show', $u) }}'" @endif>
                    @role('super_admin')
                        <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
                            @if ($u->hasRole('super_admin'))
                                <input type="checkbox" disabled aria-label="Tidak dapat memilih baris ini"
                                    class="rounded border-gray-200 text-gray-300 bg-gray-100 cursor-not-allowed w-4 h-4">
                            @else
                                <input type="checkbox" aria-label="Pilih baris ini"
                                    class="row-checkbox rounded border-gray-300 text-hijau focus:ring-hijau focus:ring-offset-0 cursor-pointer transition-colors w-4 h-4"
                                    value="{{ $u->id_users }}">
                            @endif
                        </td>
                    @endrole
                    <td class="px-4 py-3 font-medium text-gray-900 capitalize whitespace-nowrap">{{ $u->username }}
                    </td>
                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $u->email }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span
                            class="px-2 py-0.5 rounded-full bg-hijau/10 text-hijau text-[11px] font-medium border border-hijau/20 capitalize">
                            {{ $u->role }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right whitespace-nowrap" onclick="event.stopPropagation()">
                        <x-admin.ui.action-buttons :view-route="auth()->user()->can('view_user') ? route('admin.users.show', $u) : null" :edit-route="$canEdit ? route('admin.users.edit', $u) : null" :delete-route="auth()->user()->hasRole('super_admin') ? route('admin.users.destroy', $u) : null"
                            delete-message="Yakin ingin menghapus user ini?" :can-delete="$u->id_users !== auth()->id()"
                            cannot-delete-message="Tidak dapat menghapus diri sendiri" />
                    </td>
                </tr>
            @endforeach
        </x-slot:body>
        <x-slot:empty>
            Belum ada data Pengguna.
        </x-slot:empty>
    </x-admin.tables.data-table>
</div>
