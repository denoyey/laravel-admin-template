@extends('layouts.admin-nav')

@php
    $title = 'Tambah Data Upload';
    $pageTitle = 'Tambah Data';
    $pageSubtitle = 'Buat entri unggahan baru (Cover & Galeri).';
@endphp

@section('title', $title)
@section('page-title', $pageTitle)
@section('page-subtitle', $pageSubtitle)

@section('nav-menu')
    @include('pages.admin.file-upload-examples.partials.nav')
@endsection

@section('main-content')
    <div class="mb-6">
        <div class="space-y-3">
            <x-admin.ui.breadcrumb :items="[
                ['label' => 'File Upload Demo', 'url' => route('admin.file-upload-examples.index')],
                ['label' => 'Tambah'],
            ]" />
        </div>
    </div>

    <div class="w-full">
        <div class="bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                <form action="{{ route('admin.file-upload-examples.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div class="md:col-span-2">
                            <x-admin.forms.form-input name="judul" label="Judul Konten" :required="true" />
                        </div>
                        
                        <div class="md:col-span-2">
                            <x-admin.forms.form-input name="deskripsi" label="Deskripsi" type="textarea" :rows="3" :required="true" />
                        </div>
                    </div>

                    <hr class="border-gray-100 my-6">

                    <!-- Single Image Upload -->
                    <div class="mb-8">
                        <x-admin.forms.image-upload id="cover_image" name="cover_image" label="Cover Image (Single Upload)"
                            :required="false" helpText="Unggah 1 gambar sebagai cover utama. Format: JPG, PNG, WEBP. Maks 2MB." />
                    </div>

                    <hr class="border-gray-100 my-6">

                    <!-- Multi Image Upload -->
                    <div class="mt-4">
                        <x-admin.forms.multi-image-upload id="images" name="images[]" label="Galeri Gambar (Multi Upload)"
                            :required="false" helpText="Pilih beberapa gambar sekaligus untuk dijadikan galeri." />
                    </div>

                    <x-admin.forms.form-actions cancelRoute="{{ route('admin.file-upload-examples.index') }}"
                        submitLabel="Simpan Data" />
                </form>
            </div>
        </div>
    </div>
@endsection
