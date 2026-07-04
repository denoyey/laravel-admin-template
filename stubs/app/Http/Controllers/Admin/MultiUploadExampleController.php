<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MultiUploadExample;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MultiUploadExampleController extends Controller
{
    public function index()
    {
        $images = MultiUploadExample::latest()->get();
        return view('pages.admin.multi-upload-examples.index', compact('images'));
    }

    public function store(Request $request, ImageUploadService $imageService)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'compression_images' => 'nullable|integer|min:1|max:100',
        ]);

        $files = $request->file('images');
        $quality = $request->input('compression_images', 80);

        $count = count($files);

        foreach ($files as $file) {
            $path = $imageService->uploadAndConvertToWebp($file, 'examples/multi-upload', $quality);

            MultiUploadExample::create([
                'image_path' => $path,
            ]);
        }

        return redirect()->route('admin.multi-upload-examples.index')->with('success', "{$count} Gambar berhasil ditambahkan ke galeri.");
    }

    public function update(Request $request, MultiUploadExample $multiUploadExample, ImageUploadService $imageService)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'alt_text' => 'nullable|string|max:255',
            'compression_image' => 'nullable|integer|min:1|max:100',
        ]);

        $data = [
            'alt_text' => $request->input('alt_text'),
        ];

        if ($request->hasFile('image')) {
            if ($multiUploadExample->image_path && Storage::disk('public')->exists($multiUploadExample->image_path)) {
                Storage::disk('public')->delete($multiUploadExample->image_path);
            }
            $quality = $request->input('compression_image', 80);
            $data['image_path'] = $imageService->uploadAndConvertToWebp($request->file('image'), 'examples/multi-upload', $quality);
        }

        $multiUploadExample->update($data);

        return redirect()->route('admin.multi-upload-examples.index')->with('success', 'Gambar berhasil diperbarui.');
    }

    public function destroy(MultiUploadExample $multiUploadExample)
    {
        if ($multiUploadExample->image_path && Storage::disk('public')->exists($multiUploadExample->image_path)) {
            Storage::disk('public')->delete($multiUploadExample->image_path);
        }

        $multiUploadExample->delete();

        return redirect()->route('admin.multi-upload-examples.index')->with('success', 'Gambar berhasil dihapus.');
    }

    public function destroyAll()
    {
        $images = MultiUploadExample::all();

        foreach ($images as $img) {
            if ($img->image_path && Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        MultiUploadExample::query()->delete();

        return redirect()->route('admin.multi-upload-examples.index')->with('success', 'Semua gambar berhasil dihapus dari galeri.');
    }
}
