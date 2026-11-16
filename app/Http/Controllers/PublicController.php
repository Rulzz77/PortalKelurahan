<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Post;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function profile(): View
    {
        return view('public.profile', ['settings' => SiteSetting::current()]);
    }

    public function structure(): View
    {
        $staff = Staff::whereNull('parent_id')->where('is_active', true)->with(['children' => fn ($q) => $q->where('is_active', true)->with(['children' => fn ($q2) => $q2->where('is_active', true)])])->orderBy('sort_order')->get();
        return view('public.structure', ['settings' => SiteSetting::current(), 'staff' => $staff]);
    }

    public function services(): View
    {
        return view('public.services', ['settings' => SiteSetting::current(), 'services' => Service::where('is_active', true)->orderBy('sort_order')->get()]);
    }

    public function service(string $slug): View
    {
        $service = Service::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('public.service-show', ['settings' => SiteSetting::current(), 'service' => $service]);
    }

    public function news(Request $request): View
    {
        $posts = Post::published()->where('category', 'berita')
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->string('q').'%'))
            ->latest('published_at')->paginate(9)->withQueryString();
        return view('public.posts', ['settings' => SiteSetting::current(), 'posts' => $posts, 'heading' => 'Berita Kelurahan', 'category' => 'berita']);
    }

    public function announcements(): View
    {
        $posts = Post::published()->where('category', 'pengumuman')->latest('published_at')->paginate(9);
        return view('public.posts', ['settings' => SiteSetting::current(), 'posts' => $posts, 'heading' => 'Pengumuman', 'category' => 'pengumuman']);
    }

    public function post(string $slug): View
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();
        $post->increment('views');
        $related = Post::published()->where('category', $post->category)->where('id', '!=', $post->id)->latest('published_at')->take(3)->get();
        return view('public.post-show', ['settings' => SiteSetting::current(), 'post' => $post, 'related' => $related]);
    }

    public function gallery(): View
    {
        return view('public.gallery', ['settings' => SiteSetting::current(), 'items' => Gallery::where('is_published', true)->orderBy('sort_order')->latest()->paginate(12)]);
    }

    public function contact(): View
    {
        return view('public.contact', ['settings' => SiteSetting::current()]);
    }
}
