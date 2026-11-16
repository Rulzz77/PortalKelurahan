<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\Service;
use App\Models\Staff;
use App\Models\SiteSetting;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'settings' => SiteSetting::current(),
            'stats' => [
                'posts' => Post::count(),
                'services' => Service::count(),
                'staff' => Staff::count(),
                'messages' => ContactMessage::where('is_read', false)->count(),
                'gallery' => Gallery::count(),
            ],
            'recentMessages' => ContactMessage::latest()->take(5)->get(),
            'recentPosts' => Post::latest()->take(5)->get(),
        ]);
    }
}
