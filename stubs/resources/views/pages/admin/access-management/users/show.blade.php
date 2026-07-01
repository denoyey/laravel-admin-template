@extends('layouts.admin-nav')

@section('nav-menu')
    @include('pages.admin.access-management.partials.nav')
@endsection

@php
    $title = 'Detail Pengguna';
    $pageTitle = 'Detail Pengguna';
    $pageSubtitle = 'Informasi detail pengguna.';
@endphp

@section('title', $title)
@section('page-title', $pageTitle)
@section('page-subtitle', $pageSubtitle)

@section('main-content')

    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div class="space-y-3">
            <x-admin.ui.breadcrumb :items="[
                ['label' => 'Pengguna', 'url' => route('admin.users.index')],
                ['label' => $user->username],
                ['label' => 'Detail'],
            ]" />
            <h1 class="text-base sm:text-2xl font-bold text-gray-900">Detail Pengguna: <span
                    class="text-hijau">{{ $user->username }}</span></h1>
        </div>

        @if (auth()->user()->hasRole('super_admin') || (auth()->user()->can('update_user') && $user->id_users === auth()->id()))
            <a href="{{ route('admin.users.edit', $user) }}"
                class="px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 text-[13px] font-semibold rounded-md transition-colors flex items-center gap-2 border border-blue-100 w-max shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit Pengguna
            </a>
        @endif
    </div>

    <div class="bg-white border border-gray-200 rounded-md shadow-sm overflow-hidden mb-8">

        <div class="p-5">
            <div class="max-w-2xl bg-gray-50/50 border border-gray-100 rounded-md p-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 pb-4 border-b border-gray-100">
                    <div class="text-[12px] font-medium text-gray-500">Nama Lengkap</div>
                    <div class="sm:col-span-2 text-[13px] font-semibold text-gray-900">{{ $user->username }}</div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 pb-4 border-b border-gray-100">
                    <div class="text-[12px] font-medium text-gray-500">Email</div>
                    <div class="sm:col-span-2 text-[13px] font-semibold text-gray-900">{{ $user->email }}</div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 pb-4 border-b border-gray-100">
                    <div class="text-[12px] font-medium text-gray-500">Role</div>
                    <div class="sm:col-span-2">
                        <span
                            class="px-2 py-0.5 rounded-full bg-hijau/10 text-hijau text-[11px] font-medium border border-hijau/20 capitalize">
                            {{ $user->role }}
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4">
                    <div class="text-[12px] font-medium text-gray-500">Bergabung Sejak</div>
                    <div class="sm:col-span-2 text-[13px] text-gray-800">
                        {{ $user->created_at->translatedFormat('d F Y, H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
