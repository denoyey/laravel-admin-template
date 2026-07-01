@extends('layouts.admin-nav')

@section('nav-menu')
    @include('pages.admin.access-management.partials.nav')
@endsection

@php
    $title = 'Tambah Role';
    $pageTitle = 'Tambah Role';
    $pageSubtitle = 'Buat role baru beserta hak aksesnya.';
@endphp

@section('title', $title)
@section('page-title', $pageTitle)
@section('page-subtitle', $pageSubtitle)

@section('main-content')

    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div class="space-y-3">
            <x-admin.ui.breadcrumb :items="[
                ['label' => 'Roles', 'url' => route('admin.roles.index')],
                ['label' => 'Create']
            ]" />
            <h1 class="text-base sm:text-2xl font-bold text-gray-900">Create Role</h1>
        </div>
    </div>

        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-6">
            @csrf


            <div class="bg-white border border-gray-200 rounded-md shadow-sm p-4 sm:p-5 flex flex-col xl:flex-row xl:items-center justify-between gap-4 sm:gap-6">
                <div class="flex-1 w-full xl:max-w-xl">
                    <x-admin.forms.form-input
                        name="name"
                        label="Nama Role"
                        :required="true"
                        placeholder="Contoh: author, editor, manager"
                        hint="Nama role harus unik dan sebaiknya menggunakan huruf kecil."
                    />
                </div>

                <div class="flex items-start sm:items-center gap-3 bg-gray-50/80 px-3.5 py-3 rounded-md border border-gray-100 w-full xl:w-auto shrink-0">
                    <label for="global-select-all" class="relative inline-flex items-center cursor-pointer">
                        <span class="sr-only">Toggle all permissions</span>
                        <input type="checkbox" id="global-select-all" class="sr-only peer">
                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-hijau"></div>
                    </label>
                    <div>
                        <div class="text-[13px] font-bold text-gray-800 block">Select All</div>
                        <p class="text-[11px] text-gray-500 mt-0.5">Enable all Permissions currently Enabled for this role</p>
                    </div>
                </div>
            </div>


            <div class="bg-white border border-gray-200 rounded-md shadow-sm p-5">
                @include('pages.admin.access-management.roles.form-matrix')

                <x-admin.forms.form-actions
                    submit-label="Simpan Role"
                    :cancel-route="route('admin.roles.index')"
                />
            </div>
        </form>
@endsection
