<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\Post;
use App\Models\SiteSetting;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminContentManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.test',
            'password' => Hash::make('AdminStrongPass!123'),
            'role' => 'admin',
        ]);

        $this->actingAs($this->admin);
    }

    public function test_admin_can_create_service_without_optional_slug(): void
    {
        $this->post(route('admin.services.store'), [
            'name' => 'Surat Keterangan Domisili',
            'summary' => 'Informasi penerbitan surat keterangan domisili.',
            'requirements' => 'Fotokopi KTP',
            'procedure' => 'Datang ke kantor kelurahan.',
            'estimated_time' => '1 hari kerja',
            'fee_information' => 'Tidak dipungut biaya',
            'sort_order' => 1,
            'is_active' => '1',
        ])->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseHas('services', [
            'name' => 'Surat Keterangan Domisili',
            'slug' => 'surat-keterangan-domisili',
            'icon' => 'bi-file-earmark-text',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_published_post_without_optional_slug(): void
    {
        $this->post(route('admin.posts.store'), [
            'title' => 'Informasi Kerja Bakti Lingkungan',
            'category' => 'berita',
            'excerpt' => 'Kegiatan kerja bakti warga.',
            'content' => 'Kegiatan kerja bakti akan dilaksanakan sesuai jadwal yang diumumkan kantor kelurahan.',
            'status' => 'published',
        ])->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('posts', [
            'title' => 'Informasi Kerja Bakti Lingkungan',
            'slug' => 'informasi-kerja-bakti-lingkungan',
            'category' => 'berita',
            'status' => 'published',
        ]);

        $post = Post::where('slug', 'informasi-kerja-bakti-lingkungan')->firstOrFail();
        $this->assertNotNull($post->published_at);
    }

    public function test_admin_can_update_account_without_submitting_a_new_password(): void
    {
        $this->put(route('admin.account.update'), [
            'name' => 'Administrator Utama',
            'current_password' => 'AdminStrongPass!123',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'name' => 'Administrator Utama',
        ]);

        $this->assertTrue(Hash::check('AdminStrongPass!123', $this->admin->fresh()->password));
    }

    public function test_admin_can_update_site_settings_without_uploading_a_logo(): void
    {
        SiteSetting::current();

        $this->put(route('admin.settings.update'), [
            'office_name' => 'Kelurahan Contoh',
            'tagline' => 'Portal Informasi Warga',
            'office_address' => 'Jalan Merdeka Nomor 10',
            'phone' => '021123456',
            'email' => 'kantor@example.test',
            'office_hours' => 'Senin sampai Jumat, 08.00-15.00',
            'hero_title' => 'Selamat Datang',
            'hero_subtitle' => 'Informasi resmi kelurahan.',
            'about' => 'Profil kelurahan.',
            'vision' => 'Visi kelurahan.',
            'mission' => 'Misi kelurahan.',
            'office_latitude' => '-6.2000000',
            'office_longitude' => '106.8166667',
        ])->assertRedirect();

        $this->assertDatabaseHas('site_settings', [
            'office_name' => 'Kelurahan Contoh',
            'office_latitude' => '-6.2000000',
            'office_longitude' => '106.8166667',
        ]);
    }

    public function test_admin_can_upload_gallery_image(): void
    {
        Storage::fake('public');

        $response = $this->post(route('admin.galleries.store'), [
            'title' => 'Dokumentasi Kegiatan',
            'caption' => 'Kegiatan warga di lingkungan kelurahan.',
            'activity_date' => '2026-11-16',
            'sort_order' => 0,
            'is_published' => '1',
            'image' => UploadedFile::fake()->image('kegiatan.png', 800, 600),
        ]);

        $response->assertRedirect(route('admin.galleries.index'));

        $gallery = Gallery::where('title', 'Dokumentasi Kegiatan')->firstOrFail();
        Storage::disk('public')->assertExists($gallery->image_path);
        $this->assertTrue($gallery->is_published);
    }

    public function test_weak_new_password_is_rejected(): void
    {
        $this->put(route('admin.account.update'), [
            'name' => 'Administrator',
            'current_password' => 'AdminStrongPass!123',
            'password' => 'weakpassword',
            'password_confirmation' => 'weakpassword',
        ])->assertSessionHasErrors('password');
    }

    public function test_admin_can_change_to_a_strong_password(): void
    {
        $this->put(route('admin.account.update'), [
            'name' => 'Administrator',
            'current_password' => 'AdminStrongPass!123',
            'password' => 'NewStrongPassword!2026',
            'password_confirmation' => 'NewStrongPassword!2026',
        ])->assertRedirect();

        $this->assertTrue(Hash::check('NewStrongPassword!2026', $this->admin->fresh()->password));
    }

    public function test_social_media_urls_must_use_http_or_https(): void
    {
        $this->put(route('admin.settings.update'), [
            'office_name' => 'Kelurahan Contoh',
            'hero_title' => 'Selamat Datang',
            'instagram_url' => 'javascript:alert(1)',
        ])->assertSessionHasErrors('instagram_url');
    }

    public function test_gallery_rejects_non_image_uploads(): void
    {
        Storage::fake('public');

        $this->from(route('admin.galleries.create'))
            ->post(route('admin.galleries.store'), [
                'title' => 'File Tidak Valid',
                'image' => UploadedFile::fake()->createWithContent('not-an-image.jpg', 'plain text is not an image'),
            ])
            ->assertSessionHasErrors('image');

        $this->assertDatabaseCount('galleries', 0);
    }

    public function test_replacing_gallery_image_keeps_old_file_until_new_record_is_saved(): void
    {
        Storage::fake('public');
        $oldPath = 'gallery/old-photo.jpg';
        Storage::disk('public')->put($oldPath, 'old image placeholder');

        $gallery = Gallery::create([
            'title' => 'Foto Lama',
            'image_path' => $oldPath,
            'is_published' => true,
        ]);

        $this->put(route('admin.galleries.update', $gallery), [
            'title' => 'Foto Baru',
            'is_published' => '1',
            'image' => UploadedFile::fake()->image('new-photo.png', 640, 480),
        ])->assertRedirect(route('admin.galleries.index'));

        $gallery->refresh();
        $this->assertNotSame($oldPath, $gallery->image_path);
        Storage::disk('public')->assertExists($gallery->image_path);
        Storage::disk('public')->assertMissing($oldPath);
    }

    public function test_deleting_gallery_record_also_removes_its_image(): void
    {
        Storage::fake('public');
        $path = 'gallery/to-delete.jpg';
        Storage::disk('public')->put($path, 'image placeholder');

        $gallery = Gallery::create([
            'title' => 'Foto Dihapus',
            'image_path' => $path,
            'is_published' => true,
        ]);

        $this->delete(route('admin.galleries.destroy', $gallery))
            ->assertRedirect();

        $this->assertDatabaseMissing('galleries', ['id' => $gallery->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_admin_cannot_create_a_circular_staff_hierarchy(): void
    {
        $first = Staff::create([
            'name' => 'Pegawai Pertama',
            'position' => 'Lurah',
            'is_active' => true,
        ]);

        $second = Staff::create([
            'name' => 'Pegawai Kedua',
            'position' => 'Sekretaris',
            'is_active' => true,
        ]);

        $this->put(route('admin.staff.update', $first), [
            'name' => 'Pegawai Pertama',
            'position' => 'Lurah',
            'parent_id' => $second->id,
            'sort_order' => 0,
            'is_active' => '1',
        ])->assertRedirect(route('admin.staff.index'));

        $this->put(route('admin.staff.update', $second), [
            'name' => 'Pegawai Kedua',
            'position' => 'Sekretaris',
            'parent_id' => $first->id,
            'sort_order' => 0,
            'is_active' => '1',
        ])->assertSessionHasErrors('parent_id');

        $this->assertDatabaseHas('staff', [
            'id' => $second->id,
            'parent_id' => null,
        ]);
    }
}
