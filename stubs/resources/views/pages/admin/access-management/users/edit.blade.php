@extends('layouts.admin-nav')

@section('nav-menu')
    @include('pages.admin.access-management.partials.nav')
@endsection

@php
    $title = 'Edit Pengguna';
    $pageTitle = 'Edit Pengguna';
    $pageSubtitle = 'Ubah data pengguna.';
@endphp

@section('title', $title)
@section('page-title', $pageTitle)
@section('page-subtitle', $pageSubtitle)

@section('main-content')

    <div class="mb-6">
        <div class="space-y-3">
            <x-admin.ui.breadcrumb :items="[
                ['label' => 'Pengguna', 'url' => route('admin.users.index')],
                ['label' => $user->username],
                ['label' => 'Edit']
            ]" />
            <h1 class="text-base sm:text-2xl font-bold text-gray-900">Edit Pengguna: <span class="text-hijau">{{ $user->username }}</span></h1>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-md shadow-sm overflow-hidden mb-8">

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 w-full">

                <div class="space-y-5">
                    <x-admin.forms.form-input
                        name="username"
                        label="Nama Lengkap"
                        :value="$user->username"
                        :required="true"
                    />

                    <x-admin.forms.form-input
                        type="email"
                        name="email"
                        label="Email"
                        :value="$user->email"
                        :required="true"
                    />

                    @role('super_admin')
                        <x-admin.forms.form-select
                            name="role"
                            label="Role"
                            :value="$user->role"
                            :required="true"
                            :options="$roles->pluck('name', 'name')->toArray()"
                        />
                    @else
                        <div class="mb-4">
                            <label class="block text-[12px] sm:text-[13px] font-medium text-gray-700 mb-1">Role</label>
                            <input type="text" value="{{ $user->role }}" disabled
                                class="w-full text-[12px] sm:text-[13px] border border-gray-200 rounded-md px-3 py-2 bg-gray-100 text-gray-500 cursor-not-allowed">
                            <input type="hidden" name="role" value="{{ $user->role }}">
                        </div>
                    @endrole
                </div>


                @role('super_admin')
                <div class="space-y-5">
                    <x-admin.forms.form-input
                        type="password"
                        name="password"
                        label="Ganti Password"
                        placeholder="Kosongkan jika tidak diubah"
                    />

                    <x-admin.forms.form-input
                        type="password"
                        name="password_confirmation"
                        label="Konfirmasi Password Baru"
                        placeholder="Ulangi password baru"
                    />
                </div>
                @endrole
            </div>

            <x-admin.forms.form-actions
                submit-label="Simpan Perubahan"
                :cancel-route="route('admin.users.index')"
            />
        </form>
    </div>
@endsection
