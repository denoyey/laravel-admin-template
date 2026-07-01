@extends('layouts.admin')

@section('page-title', '')
@section('page-subtitle', '')

@section('content')
    <div class="w-full flex flex-col items-center justify-center min-h-[65vh]">
        <div class="w-full text-center flex flex-col items-center">

            <div
                class="text-[70px] sm:text-[80px] md:text-[90px] font-black text-black leading-none tracking-tighter mb-2 sm:mb-4 select-none">
                404
            </div>

            <h1 class="text-sm md:text-2xl font-bold text-gray-800 tracking-tight mb-2 sm:mb-3">
                Halaman Tidak Ditemukan
            </h1>

            <p class="text-xs md:text-base text-gray-500 max-w-lg mx-auto mb-6 sm:mb-8 px-4">
                Maaf, halaman yang Anda cari tidak ditemukan atau telah dipindahkan.
            </p>

            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center justify-center gap-1.5 px-4 py-1.5 sm:px-5 sm:py-2 text-[13px] sm:text-sm font-medium text-white bg-hijau hover:bg-opacity-90 rounded transition-colors">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali
            </a>

        </div>
    </div>
@endsection
