@extends('layouts.admin')

@section('title', 'Detail Aktivitas Log')
@section('page-title', 'Detail Aktivitas')
@section('page-subtitle', 'Rincian lengkap riwayat aktivitas')

@section('page-actions')
    <x-admin.ui.back-button route="{{ route('admin.activity-logs.index') }}" />
@endsection

@section('content')
    <div class="space-y-4">
        <div class="bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-semibold text-sm sm:text-base text-gray-800">Informasi Dasar</h3>
            </div>
            <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <span class="block text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Aksi
                        (Event)</span>
                    @php
                        $badgeClass = match ($activity_log->event) {
                            'created' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'updated' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'deleted' => 'bg-red-100 text-red-700 border-red-200',
                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                        };
                    @endphp
                    <span
                        class="inline-flex px-2 py-0.5 rounded-full text-[10px] sm:text-[12px] font-medium border uppercase tracking-wider {{ $badgeClass }}">
                        {{ $activity_log->event ?? 'N/A' }}
                    </span>
                </div>
                <div>
                    <span class="block text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Waktu
                        Eksekusi</span>
                    <p class="text-xs sm:text-sm text-gray-900">
                        {{ $activity_log->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB <span
                            class="text-gray-400 text-[10px] sm:text-xs ml-1">({{ $activity_log->created_at->diffForHumans() }})</span>
                    </p>
                </div>
                <div>
                    <span
                        class="block text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Pengguna
                        (Causer)</span>
                    @if ($activity_log->causer)
                        <p class="text-xs sm:text-sm text-gray-900 font-medium">{{ $activity_log->causer->username }}</p>
                        <p class="text-gray-500 text-[10px] sm:text-sm">{{ $activity_log->causer->email }}</p>
                    @else
                        <p class="text-gray-400 italic text-xs sm:text-sm">Sistem (Tanpa pengguna aktif)</p>
                    @endif
                </div>
                <div>
                    <span
                        class="block text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Target
                        Data (Subject)</span>
                    @if ($activity_log->subject_id)
                        <p
                            class="text-gray-900 font-mono text-[10px] sm:text-sm bg-gray-50 p-1.5 rounded-md w-max border border-gray-200">
                            {{ class_basename($activity_log->subject_type) }} #{{ $activity_log->subject_id }}
                        </p>
                    @else
                        <p class="text-gray-400 italic text-xs sm:text-sm">Multi data / Tidak spesifik</p>
                    @endif
                </div>
                <div class="col-span-1 md:col-span-2">
                    <span
                        class="block text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Deskripsi</span>
                    <div class="text-[10px] sm:text-sm text-gray-900 px-2 py-1.5 sm:px-3 sm:py-3 bg-gray-50/50 rounded-md border border-gray-100/80 leading-relaxed overflow-hidden"
                        title="{{ $activity_log->description }}">
                        <span class="line-clamp-2 sm:line-clamp-none">{{ $activity_log->description }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if ($activity_log->properties && count($activity_log->properties) > 0)
            @php
                $hasOld = isset($activity_log->properties['old']) && count($activity_log->properties['old']) > 0;
                $hasAttributes =
                    isset($activity_log->properties['attributes']) &&
                    count($activity_log->properties['attributes']) > 0;
                $gridClass = $hasOld && $hasAttributes ? 'lg:grid-cols-2' : 'lg:grid-cols-1';
            @endphp
            <div class="grid grid-cols-1 {{ $gridClass }} gap-4 sm:gap-6">
                @if (isset($activity_log->properties['old']) && count($activity_log->properties['old']) > 0)
                    <div class="bg-white rounded-md shadow-sm border border-red-100 overflow-hidden">
                        <div
                            class="px-4 sm:px-6 py-3 sm:py-4 border-b border-red-100 bg-red-50 flex items-center gap-2 text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="font-semibold text-xs sm:text-sm">Data Lama (Old)</h3>
                        </div>
                        <div class="p-0 overflow-x-auto">
                            <table class="w-full text-left">
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($activity_log->properties['old'] as $key => $value)
                                        <tr>
                                            <th
                                                class="px-4 sm:px-6 py-2 sm:py-3 font-medium text-[10px] sm:text-xs text-gray-600 bg-gray-50/50 w-1/3">
                                                {{ $key }}</th>
                                            <td
                                                class="px-4 sm:px-6 py-2 sm:py-3 text-red-600 font-mono text-[10px] sm:text-xs break-all">
                                                @if (is_array($value) || is_object($value))
                                                    {{ json_encode($value) }}
                                                @else
                                                    {{ $value ?? 'null' }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if (isset($activity_log->properties['attributes']) && count($activity_log->properties['attributes']) > 0)
                    <div class="bg-white rounded-md shadow-sm border border-hijau/20 overflow-hidden">
                        <div
                            class="px-4 sm:px-6 py-3 sm:py-4 border-b border-hijau/20 bg-hijau/5 flex items-center gap-2 text-hijau">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="font-semibold text-xs sm:text-sm">Data Baru (Attributes)</h3>
                        </div>
                        <div class="p-0 overflow-x-auto">
                            <table class="w-full text-left">
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($activity_log->properties['attributes'] as $key => $value)
                                        <tr>
                                            <th
                                                class="px-4 sm:px-6 py-2 sm:py-3 font-medium text-[10px] sm:text-xs text-gray-600 bg-gray-50/50 w-1/3">
                                                {{ $key }}</th>
                                            <td
                                                class="px-4 sm:px-6 py-2 sm:py-3 text-hijau font-mono text-[10px] sm:text-xs break-all">
                                                @if (is_array($value) || is_object($value))
                                                    {{ json_encode($value) }}
                                                @else
                                                    {{ $value ?? 'null' }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            @if (!isset($activity_log->properties['old']) && !isset($activity_log->properties['attributes']))
                <div class="bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-semibold text-xs sm:text-sm text-gray-800">Properti Ekstra</h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <pre
                            class="bg-gray-50 p-3 sm:p-4 rounded-md border border-gray-200 text-[10px] sm:text-xs font-mono text-gray-700 overflow-x-auto">{{ json_encode($activity_log->properties, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection
