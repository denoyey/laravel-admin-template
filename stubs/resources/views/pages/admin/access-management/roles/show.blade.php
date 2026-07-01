@extends('layouts.admin-nav')

@section('nav-menu')
    @include('pages.admin.access-management.partials.nav')
@endsection

@php
    $title = 'Detail Role';
    $pageTitle = 'Detail Role';
    $pageSubtitle = 'Informasi hak akses untuk role ini.';
@endphp

@section('title', $title)
@section('page-title', $pageTitle)
@section('page-subtitle', $pageSubtitle)

@section('main-content')

    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div class="space-y-3">
            <x-admin.ui.breadcrumb :items="[
                ['label' => 'Roles', 'url' => route('admin.roles.index')],
                ['label' => $role->name, 'class' => 'capitalize'],
                ['label' => 'View']
            ]" />
            <h1 class="text-base sm:text-2xl font-bold text-gray-900 capitalize">View {{ $role->name }}</h1>
        </div>

        <a href="{{ route('admin.roles.edit', $role) }}" class="px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 text-[13px] font-semibold rounded-md transition-colors flex items-center gap-2 border border-blue-100 w-max shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            Edit
        </a>
    </div>


    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-5 mb-6 flex flex-col md:flex-row md:items-center gap-6">
        <div class="flex-1">
            <div class="text-[12px] font-medium text-gray-500 mb-1">Nama Role</div>
            <div class="text-[14px] font-bold text-gray-900 capitalize">{{ $role->name }}</div>
        </div>
        <div class="shrink-0">
            <div class="text-[12px] font-medium text-gray-500 mb-1">Total Hak Akses</div>
            <span class="px-2.5 py-1 rounded-md bg-hijau/10 text-hijau text-[12px] font-bold border border-hijau/20 inline-block">
                {{ count($rolePermissions) }} Permissions
            </span>
        </div>
    </div>


    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-5 mb-8">
        @include('pages.admin.access-management.roles.form-matrix', ['readonly' => true])
    </div>
@endsection
