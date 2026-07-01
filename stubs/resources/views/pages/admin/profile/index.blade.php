@extends('layouts.admin')

@section('title', 'Account Settings')
@section('page-title', 'Account Settings')
@section('page-subtitle', 'Kelola informasi profil akun Anda.')

@section('breadcrumb')
    <x-admin.ui.breadcrumb :breadcrumbs="[
        ['name' => 'Account Settings', 'url' => '']
    ]" />
@endsection

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">Informasi Profil</h3>
            </div>

            <form id="profile-form" action="{{ route('admin.profile.update-info') }}" method="POST" class="p-5 space-y-5 relative">
                @csrf
                @method('PUT')
                
                <!-- Loading Overlay (JS Controlled) -->
                <div id="profile-form-loading" class="absolute inset-0 bg-white/60 backdrop-blur-[1px] z-10 flex items-center justify-center rounded-b-lg hidden">
                    <svg class="animate-spin h-6 w-6 text-hijau" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <x-admin.forms.form-input 
                    name="username" 
                    label="Username" 
                    :value="$user->username"
                    required="true"
                    placeholder="Masukkan username" />

                <x-admin.forms.form-input 
                    name="email" 
                    type="email"
                    label="Email" 
                    :value="$user->email"
                    required="true"
                    placeholder="admin@example.com" />

                <x-admin.forms.form-actions
                    submitLabel="Simpan"
                    cancelRoute="{{ url()->previous() !== url()->current() ? url()->previous() : route('admin.dashboard') }}" />
            </form>
        </div>
    </div>
@endsection
