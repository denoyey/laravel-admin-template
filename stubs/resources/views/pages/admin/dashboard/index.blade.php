@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan aktivitas sistem.')

@section('page-actions')
    <div id="stat-slider-controls" class="hidden gap-1.5">
        <button id="stat-prev"
            class="w-8 h-8 rounded-md bg-white border border-gray-200 text-gray-400 flex items-center justify-center hover:bg-gray-50 hover:text-hijau transition-colors"
            title="Sebelumnya">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <button id="stat-next"
            class="w-8 h-8 rounded-md bg-white border border-gray-200 text-gray-400 flex items-center justify-center hover:bg-gray-50 hover:text-hijau transition-colors"
            title="Selanjutnya">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>
@endsection

@section('content')
    <div id="stat-slider"
        class="grid grid-rows-1 sm:grid-rows-2 grid-flow-col auto-cols-[85vw] sm:auto-cols-[280px] md:auto-cols-[300px] xl:auto-cols-[calc(33.333%-0.75rem)] gap-3 sm:gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] scrollbar-none">

        @can('view_any_user')
            <x-admin.ui.stat-card title="Pengguna Sistem" :value="$totalUser" url="{{ route('admin.users.index') }}">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </x-slot:icon>
            </x-admin.ui.stat-card>
        @endcan

        @can('view_any_role')
            <x-admin.ui.stat-card title="Roles & Permissions" :value="$totalRole" url="{{ route('admin.roles.index') }}">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </x-slot:icon>
            </x-admin.ui.stat-card>
        @endcan

        @can('view_any_activity')
            <x-admin.ui.stat-card title="Log Aktivitas" :value="$totalActivity" url="{{ route('admin.activity-logs.index') }}">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </x-slot:icon>
            </x-admin.ui.stat-card>
        @endcan
    </div>
@endsection
