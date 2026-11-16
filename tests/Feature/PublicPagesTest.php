<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Staff;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
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

    public function test_public_structure_renders_deep_hierarchies_with_one_staff_query(): void
    {
        $parent = Staff::create([
            'name' => 'Lurah Contoh',
            'position' => 'Lurah',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        foreach ([
            ['name' => 'Sekretaris Contoh', 'position' => 'Sekretaris'],
            ['name' => 'Kepala Seksi Contoh', 'position' => 'Kepala Seksi'],
            ['name' => 'Staf Tingkat Empat', 'position' => 'Staf Pelaksana'],
        ] as $person) {
            $parent = Staff::create([
                ...$person,
                'parent_id' => $parent->id,
                'sort_order' => 0,
                'is_active' => true,
            ]);
        }

        $staffQueries = 0;
        DB::listen(static function (QueryExecuted $query) use (&$staffQueries): void {
            if (preg_match('/\bselect\b.*\bfrom\s+["`] ?staff\b/i', $query->sql) === 1) {
                $staffQueries++;
            }
        });

        $this->get(route('structure'))
            ->assertOk()
            ->assertSee('Staf Tingkat Empat');

        $this->assertSame(1, $staffQueries, 'The public organization chart should query staff once.');
    }

    public function test_public_news_search_rejects_array_and_oversized_queries(): void
    {
        $this->from(route('news'))
            ->get('/berita?q%5B%5D=informasi')
            ->assertSessionHasErrors('q');

        $this->from(route('news'))
            ->get('/berita?q=' . str_repeat('x', 101))
            ->assertSessionHasErrors('q');
    }

    public function test_public_article_escapes_untrusted_html_content(): void
    {
        $post = Post::create([
            'title' => 'Konten Uji Keamanan',
            'slug' => 'konten-uji-keamanan',
            'category' => 'berita',
            'content' => '<script>alert(1)</script><b>teks warga</b>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('posts.show', $post->slug));

        $response->assertOk();
        $response->assertDontSee('<script>', false);
        $response->assertSee('&lt;script&gt;', false);
        $response->assertSee('&lt;b&gt;teks warga&lt;/b&gt;', false);
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
