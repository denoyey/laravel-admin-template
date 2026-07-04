@extends('layouts.admin')

@php
    $title = 'Edit Data Upload';
    $pageTitle = 'Edit Data';
    $pageSubtitle = 'Ubah entri unggahan dan kelola galeri.';
@endphp

@section('title', $title)
@section('page-title', $pageTitle)
@section('page-subtitle', $pageSubtitle)

@section('main-content')
    <div class="mb-6">
        <div class="space-y-3">
            <x-admin.ui.breadcrumb :items="[
                ['label' => 'File Upload Demo', 'url' => route('admin.file-upload-examples.index')],
                ['label' => Str::limit($fileUploadExample->judul, 20), 'url' => ''],
                ['label' => 'Edit'],
            ]" />
            <h1 class="text-base sm:text-2xl font-bold text-gray-900">Edit Data: <span
                    class="text-hijau">{{ $fileUploadExample->judul }}</span></h1>
        </div>
    </div>

    <div class="w-full">
        <div class="bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                <form action="{{ route('admin.file-upload-examples.update', $fileUploadExample) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div class="md:col-span-2">
                            <x-admin.forms.form-input name="judul" label="Judul Konten" :required="true" :value="$fileUploadExample->judul" />
                        </div>
                        
                        <div class="md:col-span-2">
                            <x-admin.forms.form-input name="deskripsi" label="Deskripsi" type="textarea" :rows="3" :required="true" :value="$fileUploadExample->deskripsi" />
                        </div>
                    </div>

                    <hr class="border-gray-100 my-6">

                    <!-- Single Image Upload -->
                    <div class="mb-8">
                        <x-admin.forms.image-upload id="cover_image" name="cover_image" label="Cover Image (Single Upload)"
                            :required="false" helpText="Unggah 1 gambar sebagai cover utama. Format: JPG, PNG, WEBP. Maks 2MB." 
                            :current-image="$fileUploadExample->cover_image" />
                    </div>

                    <hr class="border-gray-100 my-6">

                    <!-- Multi Image Upload (Gallery Management) -->
                    <div class="mb-8" id="gallery-section" data-sub-service-id="{{ $fileUploadExample->id_file_upload }}"
                        data-csrf-token="{{ csrf_token() }}" data-base-url="/portal-admin/file-upload-examples">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-[13px] font-semibold text-gray-800">Manajemen Galeri (Multi-Image)</h3>
                            @if ($fileUploadExample->images->count() > 0)
                                <button type="button" id="btn-delete-all-images"
                                    class="text-[11px] text-red-500 hover:text-red-700 font-medium bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded transition-colors shadow-sm">
                                    Hapus Semua
                                </button>
                            @endif
                        </div>

                        @if ($fileUploadExample->images->count() > 0)
                            <div
                                class="w-full overflow-x-auto pb-4 scrollbar-thin bg-gray-50 p-4 rounded-md border border-gray-100">
                                <div class="flex gap-4 min-w-max">
                                    @foreach ($fileUploadExample->images as $img)
                                        <div class="w-[200px] shrink-0 bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm relative flex flex-col"
                                            id="existing-image-{{ $img->id_file_upload_image }}">
                                            <div
                                                class="relative h-32 bg-gray-100 flex items-center justify-center overflow-hidden">
                                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                                    class="w-full h-full object-cover" alt="{{ $img->alt_text }}">
                                                @if ($img->is_cover)
                                                    <div
                                                        class="absolute top-2 left-2 bg-hijau text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
                                                        COVER
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="p-3 bg-white flex-1 flex flex-col gap-3 border-t border-gray-100">
                                                <div>
                                                    <label class="block text-[10px] font-medium text-gray-500 mb-0.5">Alt
                                                        Gambar</label>
                                                    <div class="flex gap-1">
                                                        <input type="text"
                                                            id="alt-input-{{ $img->id_file_upload_image }}"
                                                            value="{{ $img->alt_text }}" placeholder="Opsional..."
                                                            class="w-full text-[10px] border border-gray-200 rounded px-1.5 py-1 focus:outline-none focus:border-hijau focus:ring-1 focus:ring-hijau transition-colors">
                                                        <button type="button"
                                                            class="btn-save-alt bg-hijau hover:bg-hijau-dark text-white rounded px-1.5 py-1 text-[9px] font-medium transition-colors shadow-sm whitespace-nowrap"
                                                            data-image-id="{{ $img->id_file_upload_image }}"
                                                            title="Simpan Alt Text">Simpan</button>
                                                    </div>
                                                </div>
                                                <div
                                                    class="flex items-center justify-between mt-auto pt-2 border-t border-gray-50">
                                                    <label class="flex items-center gap-1.5 cursor-pointer">
                                                        <input type="radio" name="existing_cover"
                                                            value="{{ $img->id_file_upload_image }}"
                                                            {{ $img->is_cover ? 'checked' : '' }}
                                                            class="btn-set-cover w-3.5 h-3.5 text-hijau focus:ring-hijau border-gray-300"
                                                            data-image-id="{{ $img->id_file_upload_image }}">
                                                        <span class="text-[11px] font-medium text-gray-700">Cover</span>
                                                    </label>

                                                    <button type="button"
                                                        class="btn-delete-image text-red-500 hover:text-red-700 text-[10px] flex items-center gap-1 font-medium transition-colors p-1 rounded hover:bg-red-50"
                                                        data-image-id="{{ $img->id_file_upload_image }}"
                                                        title="Hapus Gambar Ini">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div
                                class="bg-gray-50 border border-gray-200 border-dashed rounded-xl p-10 flex flex-col items-center justify-center text-gray-400">
                                <p class="text-[10px] font-medium">Belum ada galeri tersimpan.</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <x-admin.forms.multi-image-upload id="images" name="images[]" label="Tambah Gambar Baru"
                            :required="false" helpText="Gambar baru akan ditambahkan ke galeri (multi upload)." />
                    </div>

                    <x-admin.forms.form-actions cancelRoute="{{ route('admin.file-upload-examples.index') }}"
                        submitLabel="Simpan Data" />
                </form>
            </div>
        </div>
    </div>
@endsection
