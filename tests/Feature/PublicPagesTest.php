<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_main_public_pages_render_successfully(): void
    {
        $paths = [
            '/',
            '/profil',
            '/struktur-organisasi',
            '/layanan',
            '/berita',
            '/pengumuman',
            '/galeri',
            '/kontak',
        ];

        foreach ($paths as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_contact_form_stores_a_valid_message(): void
    {
        $this->from(route('contact'))
            ->post(route('contact.store'), [
                'name' => 'Warga Contoh',
                'email' => 'warga@example.test',
                'phone' => '081234567890',
                'subject' => 'Pertanyaan pelayanan',
                'message' => 'Saya ingin menanyakan informasi mengenai jadwal pelayanan kelurahan.',
            ])
            ->assertRedirect(route('contact'));

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Warga Contoh',
            'email' => 'warga@example.test',
            'subject' => 'Pertanyaan pelayanan',
            'is_read' => false,
        ]);
    }

    public function test_contact_form_rejects_a_too_short_message(): void
    {
        $this->from(route('contact'))
            ->post(route('contact.store'), [
                'name' => 'Warga Contoh',
                'email' => 'warga@example.test',
                'subject' => 'Pertanyaan',
                'message' => 'Singkat',
            ])
            ->assertSessionHasErrors('message');

        $this->assertDatabaseCount('contact_messages', 0);
    }
}
