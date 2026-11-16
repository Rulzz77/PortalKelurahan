<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View { return view('admin.settings', ['settings' => SiteSetting::current()]); }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'office_name' => ['required', 'string', 'max:180'], 'tagline' => ['nullable', 'string', 'max:220'],
            'office_address' => ['nullable', 'string', 'max:255'], 'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:180'], 'office_hours' => ['nullable', 'string', 'max:180'],
            'office_latitude' => ['nullable', 'numeric', 'between:-90,90'], 'office_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'hero_title' => ['required', 'string', 'max:220'], 'hero_subtitle' => ['nullable', 'string', 'max:700'],
            'about' => ['nullable', 'string'], 'vision' => ['nullable', 'string'], 'mission' => ['nullable', 'string'], 'instagram_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'], 'youtube_url' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
        unset($data['logo']);
        $settings = SiteSetting::current();
        if ($request->hasFile('logo')) {
            if ($settings->logo_path) Storage::disk('public')->delete($settings->logo_path);
            $data['logo_path'] = $request->file('logo')->store('branding', 'public');
        }
        $settings->update($data);
        return back()->with('success', 'Pengaturan website berhasil disimpan.');
    }
}
