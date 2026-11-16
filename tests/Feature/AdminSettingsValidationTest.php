<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSettingsValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin-settings@example.test',
            'password' => Hash::make('AdminStrongPass!123'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);
    }

    public function test_social_profile_urls_reject_non_http_schemes(): void
    {
        $this->put(route('admin.settings.update'), [
            'office_name' => 'Kelurahan Contoh',
            'hero_title' => 'Selamat Datang',
            'instagram_url' => 'ftp://example.test/profile',
        ])->assertSessionHasErrors('instagram_url');
    }

    public function test_social_profile_urls_accept_https(): void
    {
        $url = 'https://www.instagram.com/kelurahan.contoh';

        $this->put(route('admin.settings.update'), [
            'office_name' => 'Kelurahan Contoh',
            'hero_title' => 'Selamat Datang',
            'instagram_url' => $url,
        ])->assertRedirect();

        $this->assertDatabaseHas('site_settings', [
            'instagram_url' => $url,
        ]);
    }
}
