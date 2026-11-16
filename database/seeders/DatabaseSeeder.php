<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $defaultEmail = 'admin@kelurahan.test';
        $defaultPassword = 'GantiPassword!2026';
        $email = trim((string) config('portal.admin.email'));
        $name = trim((string) config('portal.admin.name'));
        $password = (string) config('portal.admin.password');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('ADMIN_EMAIL harus diisi dengan alamat email yang valid.');
        }

        if (mb_strlen($password) < 12) {
            throw new RuntimeException('ADMIN_PASSWORD harus memiliki minimal 12 karakter.');
        }

        if (app()->environment('production') && (
            strtolower($email) === $defaultEmail || hash_equals($defaultPassword, $password)
        )) {
            throw new RuntimeException('Ganti ADMIN_EMAIL dan ADMIN_PASSWORD bawaan sebelum menjalankan seeder di production.');
        }

        // Idempotent: seeding ulang tidak mereset password admin yang sudah ada.
        User::query()->firstOrCreate(
            ['email' => $email],
            [
                'name' => $name !== '' ? $name : 'Administrator Kelurahan',
                'password' => $password,
                'role' => 'admin',
            ]
        );

        SiteSetting::current();
    }
}
