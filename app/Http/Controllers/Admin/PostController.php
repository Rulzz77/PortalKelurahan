<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View { return view('admin.posts.index', ['posts' => Post::latest()->paginate(12)]); }
    public function create(): View { return view('admin.posts.form', ['post' => new Post()]); }
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $slugInput = $data['slug'] ?? $data['title'];
        $data['slug'] = $this->uniqueSlug($slugInput);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;
        if ($request->hasFile('cover')) $data['cover_path'] = $request->file('cover')->store('posts', 'public');
        Post::create($data);
        return redirect()->route('admin.posts.index')->with('success', 'Konten berhasil dibuat.');
    }
    public function edit(Post $post): View { return view('admin.posts.form', compact('post')); }
    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validated($request);
        // Keep the existing URL stable when the optional slug field is omitted.
        $slugInput = $data['slug'] ?? $post->slug ?? $data['title'];
        $data['slug'] = $this->uniqueSlug($slugInput, $post->id);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $post->published_at ?? now()) : null;
        if ($request->hasFile('cover')) {
            if ($post->cover_path) Storage::disk('public')->delete($post->cover_path);
            $data['cover_path'] = $request->file('cover')->store('posts', 'public');
        }
        $post->update($data);
        return redirect()->route('admin.posts.index')->with('success', 'Konten berhasil diperbarui.');
    }
    public function destroy(Post $post): RedirectResponse
    {
        if ($post->cover_path) Storage::disk('public')->delete($post->cover_path);
        $post->delete();
        return back()->with('success', 'Konten berhasil dihapus.');
    }
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'], 'slug' => ['nullable', 'string', 'max:200'],
            'category' => ['required', 'in:berita,pengumuman'], 'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string', 'min:5'], 'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'], 'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
        unset($data['cover']);

        return $data;
    }
    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'konten'; $slug = $base; $i = 2;
        while (Post::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) $slug = $base.'-'.$i++;
        return $slug;
    }
}
