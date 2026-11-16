<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View { return view('admin.galleries.index', ['items' => Gallery::orderBy('sort_order')->latest()->paginate(15)]); }
    public function create(): View { return view('admin.galleries.form', ['item' => new Gallery()]); }
    public function store(Request $request): RedirectResponse
    {
        $data = $this->data($request);
        $imagePath = $request->file('image')->store('gallery', 'public');
        if (! $imagePath) {
            return back()->withErrors(['image' => 'Foto gagal disimpan. Silakan coba kembali.'])->withInput();
        }
        $data['image_path'] = $imagePath;
        Gallery::create($data);
        return redirect()->route('admin.galleries.index')->with('success', 'Foto galeri berhasil ditambahkan.');
    }
    public function edit(Gallery $gallery): View { return view('admin.galleries.form', ['item' => $gallery]); }
    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $data = $this->data($request, false);
        $oldImagePath = $gallery->image_path;
        $newImagePath = null;
        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')->store('gallery', 'public');
            if (! $newImagePath) {
                return back()->withErrors(['image' => 'Foto gagal disimpan. Foto lama tetap digunakan.'])->withInput();
            }
            $data['image_path'] = $newImagePath;
        }
        $gallery->update($data);
        if ($newImagePath && $oldImagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }
        return redirect()->route('admin.galleries.index')->with('success', 'Foto galeri diperbarui.');
    }
    public function destroy(Gallery $gallery): RedirectResponse
    {
        $imagePath = $gallery->image_path;
        $gallery->delete();
        if ($imagePath) Storage::disk('public')->delete($imagePath);
        return back()->with('success', 'Foto galeri dihapus.');
    }
    private function data(Request $request, bool $imageRequired = true): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'], 'caption' => ['nullable', 'string', 'max:500'],
            'activity_date' => ['nullable', 'date'], 'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
        unset($data['image']);
        $data['sort_order'] = $data['sort_order'] ?? 0; $data['is_published'] = $request->boolean('is_published');
        return $data;
    }
}
