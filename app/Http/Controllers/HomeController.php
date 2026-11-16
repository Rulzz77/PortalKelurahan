<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Post;
use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('public.home', [
            'settings' => SiteSetting::current(),
            'services' => Service::where('is_active', true)->orderBy('sort_order')->take(6)->get(),
            'latestPosts' => Post::published()->latest('published_at')->take(3)->get(),
            'announcements' => Post::published()->where('category', 'pengumuman')->latest('published_at')->take(4)->get(),
            'gallery' => Gallery::where('is_published', true)->orderBy('sort_order')->latest()->take(4)->get(),
        ]);
    }
}
