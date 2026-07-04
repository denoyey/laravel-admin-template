@extends('layouts.admin-nav')

@php
    $title = 'Multi Image Gallery';
    $pageTitle = 'Multi Image Gallery';
    $pageSubtitle = 'Contoh galeri upload multi-image tanpa halaman create terpisah (seperti Logo Instansi).';
@endphp

@section('title', $title)
@section('page-title', $pageTitle)
@section('page-subtitle', $pageSubtitle)

@section('nav-menu')
    @include('pages.admin.file-upload-examples.partials.nav')
@endsection

@section('main-content')
    <div class="mb-3 bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.multi-upload-examples.store') }}" method="POST" enctype="multipart/form-data" id="form-multi-upload">
                @csrf
                <x-admin.forms.multi-image-upload id="images" name="images[]" label="Pilih Gambar untuk Galeri" :required="true" :hideCover="true" />

                <div class="pt-4 sm:pt-5 border-t border-gray-200 flex items-center justify-end">
                    <button type="submit" class="px-4 py-1.5 sm:px-5 sm:py-2 text-[11px] sm:text-[13px] font-medium text-white bg-hijau hover:bg-hijau-dark rounded-md transition-colors shadow-sm">
                        Simpan Gambar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-800">Daftar Gambar Galeri ({{ $images->count() }})</h2>
            @if ($images->count() > 0)
                <form action="{{ route('admin.multi-upload-examples.destroyAll') }}" method="POST" class="form-delete-action m-0"
                    data-message="Semua gambar galeri akan dihapus permanen.">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="flex items-center gap-1 sm:gap-1.5 px-2.5 py-1.5 sm:px-3 sm:py-1.5 text-[11px] sm:text-[12.5px] font-medium text-white hover:text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors cursor-pointer shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Semua
                    </button>
                </form>
            @endif
        </div>

        <div class="p-4 sm:p-5">
            @if ($images->count() > 0)
                <div class="flex overflow-x-auto gap-4 pb-4 snap-x snap-mandatory scrollbar-thin">
                    @foreach ($images as $img)
                        <div class="relative rounded-md overflow-hidden border border-gray-200 shadow-sm flex flex-col bg-gray-50 group shrink-0 w-28 sm:w-32 md:w-36 snap-start">
                            <div class="aspect-square w-full relative p-4 flex items-center justify-center bg-white">
                                <img src="{{ asset('storage/' . $img->image_path) }}" width="200" height="200" class="max-w-full max-h-full object-contain" alt="{{ $img->alt_text ?? 'Gambar Galeri' }}">
                            </div>

                            <div class="flex items-stretch mt-auto border-t border-gray-200 bg-gray-50/50">
                                <button type="button"
                                    class="btn-edit-logo flex-1 flex items-center justify-center gap-1.5 py-2.5 text-[11px] font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors border-r border-gray-200"
                                    data-logo-id="{{ $img->id_multi_upload }}"
                                    data-alt-text="{{ $img->alt_text }}"
                                    data-img-src="{{ asset('storage/' . $img->image_path) }}"
                                    aria-label="Edit Gambar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    <span class="hidden sm:inline">Edit</span>
                                </button>
                                
                                <form action="{{ route('admin.multi-upload-examples.destroy', $img) }}" method="POST"
                                    class="form-delete-action flex-1 flex m-0"
                                    data-message="Gambar ini akan dihapus permanen dari galeri.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full flex-1 flex items-center justify-center gap-1.5 py-2.5 text-[11px] font-medium text-gray-600 hover:text-red-600 hover:bg-red-50 transition-colors"
                                        aria-label="Hapus Gambar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span class="hidden sm:inline">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <h3 class="text-[11px] font-medium text-gray-400">Belum ada gambar dalam galeri.</h3>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editLogoModal" class="fixed inset-0 z-100 hidden" data-base-route="{{ route('admin.multi-upload-examples.index') }}">
        <div class="absolute inset-0 bg-gray-900/70 transition-opacity opacity-0" id="editLogoModalBackdrop"></div>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="relative bg-white rounded-md shadow-2xl text-left overflow-hidden transform transition-all sm:my-8 sm:max-w-md w-full scale-95 opacity-0"
                id="editLogoModalContent">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-800">Edit Gambar Galeri</h3>
                    <button type="button" class="btn-close-modal text-gray-400 hover:text-gray-600 transition-colors"
                        aria-label="Tutup modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="editLogoForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-5">
                        <div class="mb-4">
                            <x-admin.forms.image-upload id="edit_image" name="image"
                                label="Ubah Gambar (Opsional)" :required="false" />
                        </div>

                        <div>
                            <label for="edit_alt_text"
                                class="block text-[12px] sm:text-[13px] font-medium text-gray-700 mb-1">Alt Text (SEO)</label>
                            <input type="text" id="edit_alt_text" name="alt_text"
                                class="w-full text-[12px] sm:text-[13px] border border-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-hijau/20 focus:border-hijau transition-all"
                                placeholder="Teks alternatif untuk gambar ini...">
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                        <button type="button"
                            class="btn-close-modal px-4 py-2 text-[12px] font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-[12px] font-medium text-white bg-hijau hover:bg-hijau-dark rounded-md transition-colors shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('editLogoModal');
        const backdrop = document.getElementById('editLogoModalBackdrop');
        const modalContent = document.getElementById('editLogoModalContent');
        const closeBtns = document.querySelectorAll('.btn-close-modal');
        const editForm = document.getElementById('editLogoForm');
        const altInput = document.getElementById('edit_alt_text');
        
        const baseRoute = modal.getAttribute('data-base-route');

        function openModal() {
            modal.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                modalContent.classList.remove('opacity-0', 'scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeModal() {
            backdrop.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        document.querySelectorAll('.btn-edit-logo').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-logo-id');
                const alt = this.getAttribute('data-alt-text');
                
                editForm.action = `${baseRoute}/${id}`;
                altInput.value = alt || '';
                
                // Trigger global AdminCropper or single-image event if necessary to reset preview
                const previewImg = document.getElementById('edit_image-preview');
                const resetBtn = document.getElementById('edit_image-btn-reset');
                if (previewImg && resetBtn) {
                    resetBtn.click();
                }

                openModal();
            });
        });

        closeBtns.forEach(btn => {
            btn.addEventListener('click', closeModal);
        });

        backdrop.addEventListener('click', closeModal);
    });
</script>
@endpush
