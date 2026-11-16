<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
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
