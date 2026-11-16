<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_rate_limited_after_repeated_failures(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('admin.login.submit'), [
                'email' => 'unknown@example.test',
                'password' => 'WrongPassword!123',
            ])->assertRedirect();
        }

        $this->post(route('admin.login.submit'), [
            'email' => 'unknown@example.test',
            'password' => 'WrongPassword!123',
        ])->assertStatus(429);
    }

    public function test_guest_cannot_open_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_new_user_role_defaults_to_non_admin(): void
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'new-user@example.test',
            'password' => Hash::make('UserStrongPass!123'),
        ]);

        $this->assertSame('user', $user->fresh()->role);
        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_guest_cannot_submit_admin_content(): void
    {
        $this->post(route('admin.services.store'), [
            'name' => 'Layanan Tidak Sah',
        ])->assertRedirect(route('admin.login'));

        $this->assertDatabaseCount('services', 0);
    }

    public function test_admin_can_log_in_and_open_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.test',
            'password' => Hash::make('AdminStrongPass!123'),
            'role' => 'admin',
        ]);

        $this->post(route('admin.login.submit'), [
            'email' => 'admin@example.test',
            'password' => 'AdminStrongPass!123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
        $this->get(route('admin.dashboard'))->assertOk();
    }

    public function test_non_admin_account_cannot_log_in_to_admin_panel(): void
    {
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.test',
            'password' => Hash::make('UserStrongPass!123'),
            'role' => 'user',
        ]);

        $this->post(route('admin.login.submit'), [
            'email' => 'user@example.test',
            'password' => 'UserStrongPass!123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_log_out_and_session_is_cleared(): void
    {
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.test',
            'password' => Hash::make('AdminStrongPass!123'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
