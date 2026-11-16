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
        $data['image_path'] = $request->file('image')->store('gallery', 'public');
        Gallery::create($data);
        return redirect()->route('admin.galleries.index')->with('success', 'Foto galeri berhasil ditambahkan.');
    }
    public function edit(Gallery $gallery): View { return view('admin.galleries.form', ['item' => $gallery]); }
    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $data = $this->data($request, false);
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($gallery->image_path);
            $data['image_path'] = $request->file('image')->store('gallery', 'public');
        }
        $gallery->update($data);
        return redirect()->route('admin.galleries.index')->with('success', 'Foto galeri diperbarui.');
    }
    public function destroy(Gallery $gallery): RedirectResponse
    {
        Storage::disk('public')->delete($gallery->image_path);
        $gallery->delete();
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
