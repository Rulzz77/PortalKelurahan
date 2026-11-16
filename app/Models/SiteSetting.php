<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['office_name', 'tagline', 'logo_path', 'office_address', 'phone', 'email', 'office_hours', 'office_latitude', 'office_longitude', 'hero_title', 'hero_subtitle', 'about', 'vision', 'mission', 'instagram_url', 'facebook_url', 'youtube_url'];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'office_name' => 'Nama Kelurahan',
            'tagline' => 'Portal Informasi dan Pelayanan Masyarakat',
            'hero_title' => 'Selamat Datang di Portal Informasi Kelurahan',
            'hero_subtitle' => 'Sumber informasi resmi mengenai kegiatan, pengumuman, dan pelayanan masyarakat.',
        ]);
    }
}
