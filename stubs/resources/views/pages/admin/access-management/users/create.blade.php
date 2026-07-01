@extends('layouts.admin-nav')

@section('nav-menu')
    @include('pages.admin.access-management.partials.nav')
@endsection

@php
    $title = 'Tambah Pengguna';
    $pageTitle = 'Tambah Pengguna';
    $pageSubtitle = 'Tambahkan pengguna baru ke portal administrasi.';
@endphp

@section('title', $title)
@section('page-title', $pageTitle)
@section('page-subtitle', $pageSubtitle)

@section('main-content')

    <div class="mb-6">
        <div class="space-y-3">
            <x-admin.ui.breadcrumb :items="[['label' => 'Pengguna', 'url' => route('admin.users.index')], ['label' => 'Tambah']]" />
            <h1 class="text-base sm:text-2xl font-bold text-gray-900">Tambah Pengguna</h1>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-md shadow-sm overflow-hidden mb-8">

        <form action="{{ route('admin.users.store') }}" method="POST" class="p-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 w-full">

                <div class="space-y-5">
                    <x-admin.forms.form-input name="username" label="Nama Lengkap" :required="true"
                        placeholder="Masukkan nama lengkap" autocomplete="name" />

                    <x-admin.forms.form-input type="email" name="email" label="Email" :required="true"
                        placeholder="email@domain.com" autocomplete="email" />

                    <x-admin.forms.form-select name="role" label="Role" :required="true" :options="$roles->pluck('name', 'name')->toArray()" />
                </div>


                <div class="space-y-5">
                    <x-admin.forms.form-input type="password" name="password" label="Password" :required="true"
                        placeholder="Minimal 8 karakter" autocomplete="new-password" />

                    <x-admin.forms.form-input type="password" name="password_confirmation" label="Konfirmasi Password"
                        :required="true" placeholder="Ulangi password" autocomplete="new-password" />
                </div>
            </div>

            <x-admin.forms.form-actions submit-label="Simpan Pengguna" :cancel-route="route('admin.users.index')" />
        </form>
    </div>
@endsection
