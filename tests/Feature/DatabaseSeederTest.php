<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_an_admin_and_initial_site_settings(): void
    {
        config([
            'portal.admin.name' => 'Admin Test',
            'portal.admin.email' => 'admin@kelurahan.test',
            'portal.admin.password' => 'SeedStrongPass!123',
        ]);

        $this->seed();

        $admin = User::where('email', 'admin@kelurahan.test')->firstOrFail();

        $this->assertSame('admin', $admin->role);
        $this->assertTrue(Hash::check('SeedStrongPass!123', $admin->password));
        $this->assertDatabaseHas('site_settings', [
            'id' => 1,
            'office_name' => 'Nama Kelurahan',
        ]);
    }

    public function test_database_seeder_does_not_reset_an_existing_admin_password(): void
    {
        config([
            'portal.admin.name' => 'Admin Test',
            'portal.admin.email' => 'admin@kelurahan.test',
            'portal.admin.password' => 'SeedStrongPass!123',
        ]);

        $admin = User::create([
            'name' => 'Admin Existing',
            'email' => 'admin@kelurahan.test',
            'password' => 'ExistingStrongPass!123',
            'role' => 'admin',
        ]);

        $this->seed();

        $this->assertSame($admin->id, User::where('email', 'admin@kelurahan.test')->value('id'));
        $this->assertTrue(Hash::check('ExistingStrongPass!123', $admin->fresh()->password));
    }
}
