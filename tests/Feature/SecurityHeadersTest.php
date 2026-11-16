<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_responses_include_baseline_security_headers(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()')
            ->assertHeader('X-Permitted-Cross-Domain-Policies', 'none');
    }

    public function test_admin_responses_are_not_stored_by_browser_caches(): void
    {
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin-cache@example.test',
            'password' => Hash::make('AdminStrongPass!123'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertHeader('Cache-Control', 'private, no-store, max-age=0');
    }

    public function test_https_responses_include_hsts_header(): void
    {
        $this->withServerVariables(['HTTPS' => 'on'])
            ->get('/')
            ->assertOk()
            ->assertHeader('Strict-Transport-Security', 'max-age=31536000');
    }
}
