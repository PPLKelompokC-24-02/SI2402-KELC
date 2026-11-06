<?php

namespace Tests\Feature;

use App\Models\CareerEvent;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminCareerEventWebTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    private function validPayload(array $overrides = []): array
    {
        return array_replace([
            'title' => 'Simulasi Career Fair Telkom 2026',
            'category' => 'Career Fair',
            'description' => 'Kegiatan pameran lowongan kerja dan konsultasi karier bersama puluhan perusahaan mitra.',
            'organizer' => 'CDC Telkom University',
            'start_at' => '2026-11-25 09:00:00',
            'end_at' => '2026-11-25 17:00:00',
            'location' => 'Auditorium Gd. Damar',
            'poster' => UploadedFile::fake()->image('poster.jpg', 600, 800),
            'status' => 'published',
            'registration_url' => 'https://cdc.telkomuniversity.ac.id/register',
        ], $overrides);
    }

    /**
     * AC-01: Admin dapat membuka form dan menambahkan event baru.
     */
    public function test_admin_can_open_create_page_and_store_event(): void
    {
        $admin = $this->user('admin');

        // Buka form create
        $this->actingAs($admin, 'web')
            ->get(route('admin.events.create'))
            ->assertOk()
            ->assertSee('Tambah Career Event Baru')
            ->assertSee('Informasi Dasar Kegiatan');

        // Submit data event baru (termasuk upload poster)
        $payload = $this->validPayload();
        $response = $this->actingAs($admin, 'web')
            ->post(route('admin.events.store'), $payload);

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('career_events', [
            'title' => 'Simulasi Career Fair Telkom 2026',
            'category' => 'Career Fair',
            'organizer' => 'CDC Telkom University',
            'status' => 'published',
            'registration_url' => 'https://cdc.telkomuniversity.ac.id/register',
        ]);

        $event = CareerEvent::where('title', 'Simulasi Career Fair Telkom 2026')->firstOrFail();
        $this->assertNotNull($event->poster_path);
        Storage::disk('local')->assertExists($event->poster_path);
    }

    /**
     * AC-01: Validasi input gagal jika field wajib tidak diisi.
     */
    public function test_store_event_validation_fails_when_required_fields_missing(): void
    {
        $admin = $this->user('admin');

        $response = $this->actingAs($admin, 'web')
            ->post(route('admin.events.store'), []);

        $response->assertSessionHasErrors([
            'title', 'category', 'description', 'organizer', 'start_at', 'location', 'registration_url',
        ]);
    }

    /**
     * AC-01: Mahasiswa tidak diizinkan menambahkan event (403 Forbidden).
     */
    public function test_student_cannot_create_or_store_event(): void
    {
        $student = $this->user('mahasiswa');

        $this->actingAs($student, 'web')
            ->get(route('admin.events.create'))
            ->assertForbidden();

        $this->actingAs($student, 'web')
            ->post(route('admin.events.store'), $this->validPayload())
            ->assertForbidden();
    }

    /**
     * AC-02: Admin dapat melihat daftar event beserta filter kategori dan pencarian.
     */
    public function test_admin_can_view_event_list_and_filter_by_category(): void
    {
        $admin = $this->user('admin');

        // Pastikan halaman index memuat event dan filter kategori
        $response = $this->actingAs($admin, 'web')
            ->get(route('admin.events.index'))
            ->assertOk()
            ->assertSee('Manajemen Career Event')
            ->assertSee('Semua Kategori')
            ->assertSee('Webinar')
            ->assertSee('Workshop');

        // Uji filter kategori
        $filterResponse = $this->actingAs($admin, 'web')
            ->get(route('admin.events.index', ['category' => 'Workshop']))
            ->assertOk()
            ->assertSee('Building Production-Ready REST API with Laravel')
            ->assertDontSee('Annual Career Fair 2026');
    }

    /**
     * AC-02: Admin dapat melihat detail event berdasarkan ID dan 404 jika tidak ditemukan.
     */
    public function test_admin_can_view_event_detail_and_handles_not_found(): void
    {
        $admin = $this->user('admin');
        $event = CareerEvent::firstOrFail();

        // Detail ditemukan
        $this->actingAs($admin, 'web')
            ->get(route('admin.events.show', $event))
            ->assertOk()
            ->assertSee($event->title)
            ->assertSee($event->organizer)
            ->assertSee($event->registration_url);

        // ID tidak ditemukan
        $this->actingAs($admin, 'web')
            ->get('/admin/events/999999')
            ->assertNotFound();
    }

    /**
     * AC-03: Admin dapat mengubah informasi event dan mengganti poster.
     */
    public function test_admin_can_edit_and_update_event_information_and_poster(): void
    {
        $admin = $this->user('admin');
        $event = CareerEvent::firstOrFail();

        // Buka form edit
        $this->actingAs($admin, 'web')
            ->get(route('admin.events.edit', $event))
            ->assertOk()
            ->assertSee('Edit Career Event')
            ->assertSee($event->title);

        // Update informasi dan poster baru
        $newPoster = UploadedFile::fake()->image('new_poster.png', 800, 1000);
        $updatePayload = [
            'title' => 'Judul Event Telah Diperbarui',
            'category' => 'Webinar',
            'description' => 'Deskripsi kegiatan baru yang telah disesuaikan.',
            'organizer' => 'Panitia Career Compass 2026',
            'start_at' => '2026-12-01 10:00:00',
            'end_at' => '2026-12-01 12:00:00',
            'location' => 'Online Zoom',
            'poster' => $newPoster,
            'status' => 'published',
            'registration_url' => 'https://zoom.us/j/1234567890',
        ];

        $response = $this->actingAs($admin, 'web')
            ->put(route('admin.events.update', $event), $updatePayload);

        $response->assertRedirect(route('admin.events.show', $event));
        $response->assertSessionHas('status');

        $event->refresh();
        $this->assertEquals('Judul Event Telah Diperbarui', $event->title);
        $this->assertEquals('Webinar', $event->category);
        $this->assertNotNull($event->poster_path);
        Storage::disk('local')->assertExists($event->poster_path);

        // Update pada event yang tidak ada mengembalikan 404
        $this->actingAs($admin, 'web')
            ->put('/admin/events/999999', $updatePayload)
            ->assertNotFound();
    }

    /**
     * AC-05: Sistem memvalidasi data wajib, format tanggal, dan format tautan pendaftaran.
     */
    public function test_ac_05_system_validates_required_fields_date_format_and_registration_url(): void
    {
        $admin = $this->user('admin');

        // 1. Data wajib kosong
        $responseRequired = $this->actingAs($admin, 'web')
            ->post(route('admin.events.store'), []);

        $responseRequired->assertSessionHasErrors([
            'title', 'category', 'description', 'organizer', 'start_at', 'location', 'registration_url',
        ]);

        // 2. Format tanggal salah
        $responseInvalidDate = $this->actingAs($admin, 'web')
            ->post(route('admin.events.store'), array_replace($this->validPayload(), [
                'start_at' => 'bukan-format-tanggal-valid',
            ]));

        $responseInvalidDate->assertSessionHasErrors(['start_at']);

        // 3. Format tautan pendaftaran salah (bukan URL atau tidak diawali http/https)
        $responseInvalidUrl = $this->actingAs($admin, 'web')
            ->post(route('admin.events.store'), array_replace($this->validPayload(), [
                'registration_url' => 'bukan-link-url',
            ]));

        $responseInvalidUrl->assertSessionHasErrors(['registration_url']);

        $responseNoProtocol = $this->actingAs($admin, 'web')
            ->post(route('admin.events.store'), array_replace($this->validPayload(), [
                'registration_url' => 'ftp://cdc.telkomuniversity.ac.id/register',
            ]));

        $responseNoProtocol->assertSessionHasErrors(['registration_url']);
    }

    /**
     * AC-06: Sistem menampilkan informasi event yang telah diperbarui sesuai perubahan Admin.
     */
    public function test_ac_06_system_displays_updated_event_information(): void
    {
        $admin = $this->user('admin');
        $event = CareerEvent::firstOrFail();

        $updatedData = [
            'title' => 'Webinar Eksplorasi Karier UI/UX 2026',
            'category' => 'Webinar',
            'description' => 'Sesi berbagi wawasan karier desain produk digital untuk mahasiswa.',
            'organizer' => 'CDC Telkom University & UI/UX Club',
            'start_at' => '2026-12-15 14:00:00',
            'end_at' => '2026-12-15 16:30:00',
            'location' => 'Zoom Meeting ID: 888 999 111',
            'status' => 'published',
            'registration_url' => 'https://event.telkomuniversity.ac.id/uiux-2026',
        ];

        // Admin melakukan update
        $response = $this->actingAs($admin, 'web')
            ->put(route('admin.events.update', $event), $updatedData);

        // Sistem mengarahkan ke halaman detail dan menampilkan data yang telah diperbarui
        $response->assertRedirect(route('admin.events.show', $event));

        $showResponse = $this->actingAs($admin, 'web')
            ->get(route('admin.events.show', $event));

        $showResponse->assertOk()
            ->assertSee('Webinar Eksplorasi Karier UI/UX 2026')
            ->assertSee('CDC Telkom University & UI/UX Club')
            ->assertSee('Sesi berbagi wawasan karier desain produk digital untuk mahasiswa.')
            ->assertSee('Zoom Meeting ID: 888 999 111')
            ->assertSee('https://event.telkomuniversity.ac.id/uiux-2026');

        // Pastikan juga muncul di halaman daftar (index)
        $this->actingAs($admin, 'web')
            ->get(route('admin.events.index'))
            ->assertOk()
            ->assertSee('Webinar Eksplorasi Karier UI/UX 2026');
    }

    /**
     * AC-04: Admin dapat menghapus event dan poster dari database & storage.
     */
    public function test_admin_can_delete_event_and_handles_not_found(): void
    {
        $admin = $this->user('admin');

        // Buat event dummy dengan poster
        $poster = UploadedFile::fake()->image('delete_me.jpg');
        $posterPath = $poster->store('events', 'local');

        $event = CareerEvent::create([
            'title' => 'Event Yang Akan Dihapus',
            'slug' => 'event-yang-akan-dihapus',
            'category' => 'Webinar',
            'description' => 'Akan dihapus',
            'organizer' => 'Tester',
            'start_at' => '2026-11-30 09:00:00',
            'location' => 'Ruang 101',
            'poster_path' => $posterPath,
            'status' => 'published',
            'registration_url' => 'https://example.com/register',
        ]);

        Storage::disk('local')->assertExists($posterPath);

        // Admin menghapus event
        $response = $this->actingAs($admin, 'web')
            ->delete(route('admin.events.destroy', $event));

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('status');

        $this->assertDatabaseMissing('career_events', ['id' => $event->id]);
        Storage::disk('local')->assertMissing($posterPath);

        // Hapus event yang tidak ada mengembalikan 404
        $this->actingAs($admin, 'web')
            ->delete('/admin/events/999999')
            ->assertNotFound();
    }
}
