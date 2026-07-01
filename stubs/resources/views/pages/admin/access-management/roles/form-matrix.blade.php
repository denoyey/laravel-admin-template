<div class="space-y-6">
    <div class="flex items-center gap-4 border-b border-gray-200 pb-3 mb-4">
        <h2 class="text-[15px] font-bold text-gray-800">Hak Akses (Permissions)</h2>
    </div>


    <div class="flex flex-wrap items-center gap-2 mb-6 permission-tabs">
        <button type="button" data-target="resources"
            class="tab-btn active px-4 py-2 bg-hijau/10 text-hijau rounded-md text-[13px] font-bold flex items-center gap-2 border border-hijau/20 transition-colors">
            Resources
            <span
                class="tab-badge bg-white text-hijau px-1.5 py-0.5 rounded text-[11px] font-bold border border-hijau/20 shadow-sm">{{ count($groupedPermissions) }}</span>
        </button>
        @if (count($customPermissions) > 0)
            <button type="button" data-target="custom"
                class="tab-btn px-4 py-2 bg-white text-gray-600 hover:bg-gray-50 rounded-md text-[13px] font-bold flex items-center gap-2 border border-transparent transition-colors">
                Custom Permissions
                <span
                    class="tab-badge bg-hijau/10 text-hijau px-1.5 py-0.5 rounded text-[11px] font-bold">{{ count($customPermissions) }}</span>
            </button>
        @endif
    </div>


    <div id="section-resources"
        class="permission-section {{ isset($readonly) && $readonly ? 'pointer-events-none opacity-80' : '' }}">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach ($groupedPermissions as $resource => $data)
                <div
                    class="bg-white border border-gray-200 rounded-md shadow-sm overflow-hidden permission-card transition-all duration-200">
                    <div class="p-3.5 border-b border-gray-100 bg-gray-50/80 flex justify-between items-center">
                        <div>
                            <h3 class="text-[14px] font-bold text-gray-900 capitalize">{{ $resource }}</h3>
                            <p class="text-[11px] text-gray-500 mt-0.5">{{ Str::after($data['label'], '(') ? rtrim(Str::after($data['label'], '('), ')') : 'App\Models\\'.ucfirst($resource) }}</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <label
                            class="flex items-center gap-2.5 mb-3.5 pb-2.5 border-b border-gray-100 cursor-pointer group">
                            <input type="checkbox"
                                class="select-all-checkbox w-4 h-4 text-hijau border-gray-300 rounded focus:ring-hijau transition-colors cursor-pointer"
                                aria-label="Select all permissions for {{ $resource }}">
                            <span
                                class="text-[13px] font-semibold text-hijau group-hover:text-hijau-dark transition-colors">Select
                                all</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-3">
                            @foreach ($data['permissions'] as $perm)
                                <label class="flex items-start gap-2 cursor-pointer group">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm }}"
                                        class="permission-checkbox mt-[2px] w-4 h-4 shrink-0 text-hijau border-gray-300 rounded focus:ring-hijau transition-colors cursor-pointer"
                                        {{ in_array($perm, $rolePermissions ?? []) ? 'checked' : '' }}>
                                    <span
                                        class="text-[12.5px] font-medium text-gray-600 group-hover:text-gray-900 transition-colors capitalize leading-snug">
                                        {{ ucwords(str_replace('_', ' ', str_replace('_' . $resource, '', $perm))) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    @if (count($customPermissions) > 0)
        <div id="section-custom"
            class="permission-section hidden mt-4 {{ isset($readonly) && $readonly ? 'pointer-events-none opacity-80' : '' }}">
            <h3 class="text-[14px] font-bold text-gray-800 mb-4">Custom Permissions</h3>
            <div class="bg-white border border-gray-200 rounded-md shadow-sm p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach ($customPermissions as $perm => $label)
                        <label class="flex items-start gap-2.5 cursor-pointer group">
                            <input type="checkbox" name="permissions[]" value="{{ $perm }}"
                                class="mt-[2px] w-4 h-4 shrink-0 text-hijau border-gray-300 rounded focus:ring-hijau transition-colors cursor-pointer"
                                {{ in_array($perm, $rolePermissions ?? []) ? 'checked' : '' }}>
                            <span
                                class="text-[13px] text-gray-700 font-medium group-hover:text-gray-900 leading-snug">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
