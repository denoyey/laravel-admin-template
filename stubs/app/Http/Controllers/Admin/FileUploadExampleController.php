<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileUploadExample;
use App\Models\FileUploadExampleImage;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class FileUploadExampleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:access_admin_panel'),
        ];
    }

    public function index(Request $request)
    {
        return view('pages.admin.file-upload-examples.index');
    }

    public function create()
    {
        return view('pages.admin.file-upload-examples.create');
    }

    public function store(Request $request, ImageUploadService $imageService)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'deskripsi' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'compression_cover' => 'nullable|integer|min:1|max:100',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'new_alts' => 'nullable|array',
            'new_cover' => 'nullable|integer',
            'compression_images' => 'nullable|integer|min:1|max:100',
        ]);

        $data = $request->only(['judul', 'deskripsi']);

        // Handle Single Image Upload (Cover Image)
        if ($request->hasFile('cover_image')) {
            $quality = $request->input('compression_cover', 80);
            $data['cover_image'] = $imageService->uploadAndConvertToWebp($request->file('cover_image'), 'content/file-upload-examples/covers', $quality);
        }

        $fileUpload = FileUploadExample::create($data);

        // Handle Multi-Image Upload (Gallery)
        if ($request->hasFile('images')) {
            $quality = $request->input('compression_images', 80);
            foreach ($request->file('images') as $index => $file) {
                $path = $imageService->uploadAndConvertToWebp($file, 'content/file-upload-examples/gallery', $quality);
                $isCover = ($request->input('new_cover') !== null && (int) $request->input('new_cover') === $index) ? true : false;

                if ($index === 0 && $request->input('new_cover') === null) {
                    $isCover = true;
                }

                $fileUpload->images()->create([
                    'image_path' => $path,
                    'alt_text' => $request->input("new_alts.{$index}"),
                    'is_cover' => $isCover,
                ]);
            }
        }

        return redirect()->route('admin.file-upload-examples.index')->with('success', 'Data berhasil dibuat.');
    }

    public function edit(FileUploadExample $fileUploadExample)
    {
        $fileUploadExample->load('images');
        return view('pages.admin.file-upload-examples.edit', compact('fileUploadExample'));
    }

    public function update(Request $request, FileUploadExample $fileUploadExample, ImageUploadService $imageService)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'deskripsi' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'compression_cover' => 'nullable|integer|min:1|max:100',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'new_alts' => 'nullable|array',
            'new_cover' => 'nullable|integer',
            'compression_images' => 'nullable|integer|min:1|max:100',
        ]);

        $data = $request->only(['judul', 'deskripsi']);

        // Handle Single Image Upload
        if ($request->hasFile('cover_image')) {
            // Hapus cover lama
            if ($fileUploadExample->cover_image && Storage::disk('public')->exists($fileUploadExample->cover_image)) {
                Storage::disk('public')->delete($fileUploadExample->cover_image);
            }

            $quality = $request->input('compression_cover', 80);
            $data['cover_image'] = $imageService->uploadAndConvertToWebp($request->file('cover_image'), 'content/file-upload-examples/covers', $quality);
        }

        $fileUploadExample->update($data);

        // Handle Multi-Image Upload
        if ($request->hasFile('images')) {
            $hasNewCover = $request->input('new_cover') !== null;

            if ($hasNewCover) {
                $fileUploadExample->images()->update(['is_cover' => false]);
            }

            $quality = $request->input('compression_images', 80);
            foreach ($request->file('images') as $index => $file) {
                $path = $imageService->uploadAndConvertToWebp($file, 'content/file-upload-examples/gallery', $quality);
                $isCover = ($hasNewCover && (int) $request->input('new_cover') === $index) ? true : false;

                if ($index === 0 && ! $hasNewCover && $fileUploadExample->images()->count() === 0) {
                    $isCover = true;
                }

                $fileUploadExample->images()->create([
                    'image_path' => $path,
                    'alt_text' => $request->input("new_alts.{$index}"),
                    'is_cover' => $isCover,
                ]);
            }
        }

        return redirect()->route('admin.file-upload-examples.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(FileUploadExample $fileUploadExample)
    {
        // Delete Single Cover
        if ($fileUploadExample->cover_image && Storage::disk('public')->exists($fileUploadExample->cover_image)) {
            Storage::disk('public')->delete($fileUploadExample->cover_image);
        }

        // Delete Multi-Images
        foreach ($fileUploadExample->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $fileUploadExample->delete();

        return redirect()->route('admin.file-upload-examples.index')->with('success', 'Data berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
        ]);

        $ids = json_decode($request->input('ids'), true);
        if (! is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih.');
        }

        $ids = array_slice(array_unique(array_filter(array_map('intval', $ids))), 0, 100);
        $examples = FileUploadExample::whereIn('id_file_upload', $ids)->get();
        $count = $examples->count();

        if ($count === 0) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        activity()->withoutLogs(function () use ($examples) {
            foreach ($examples as $example) {
                if ($example->cover_image && Storage::disk('public')->exists($example->cover_image)) {
                    Storage::disk('public')->delete($example->cover_image);
                }
                foreach ($example->images as $image) {
                    if (Storage::disk('public')->exists($image->image_path)) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                }
                $example->delete();
            }
        });

        return redirect()->back()->with('success', $count.' Data berhasil dihapus.');
    }

    public function updateImage(Request $request, $id_image)
    {
        $image = FileUploadExampleImage::findOrFail($id_image);

        $request->validate([
            'alt_text' => 'nullable|string|max:150',
            'is_cover' => 'nullable|boolean',
        ]);

        if ($request->has('is_cover') && $request->is_cover) {
            FileUploadExampleImage::where('id_file_upload', $image->id_file_upload)
                ->where('id_file_upload_image', '!=', $image->id_file_upload_image)
                ->update(['is_cover' => false]);
            $image->is_cover = true;
            session()->flash('success', 'Gambar cover berhasil diperbarui.');
        }

        if ($request->has('alt_text')) {
            $image->alt_text = $request->alt_text;
        }

        $image->save();

        return response()->json(['success' => true, 'message' => 'Gambar berhasil diperbarui']);
    }

    public function destroyImage($id_image)
    {
        $image = FileUploadExampleImage::findOrFail($id_image);

        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $id_file_upload = $image->id_file_upload;
        $wasCover = $image->is_cover;
        $image->delete();

        if ($wasCover) {
            $firstImage = FileUploadExampleImage::where('id_file_upload', $id_file_upload)->first();
            if ($firstImage) {
                $firstImage->update(['is_cover' => true]);
            }
        }

        session()->flash('success', 'Gambar berhasil dihapus.');

        return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus']);
    }

    public function destroyAllImages($id_file_upload)
    {
        $fileUpload = FileUploadExample::findOrFail($id_file_upload);
        foreach ($fileUpload->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }

        session()->flash('success', 'Semua gambar berhasil dihapus.');

        return response()->json(['success' => true, 'message' => 'Semua gambar berhasil dihapus']);
    }
}
