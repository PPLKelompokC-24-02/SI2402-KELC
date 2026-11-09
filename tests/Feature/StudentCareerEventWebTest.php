<?php

namespace Tests\Feature;

use App\Models\CareerEvent;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * US-19 — Feature Test: Career Event untuk Mahasiswa
 *
 * Mencakup AC-01 sampai AC-08.
 */
class StudentCareerEventWebTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    // =========================================================
    // AC-01: Menampilkan daftar Career Event published
    // =========================================================

    /**
     * AC-01: Mahasiswa dapat membuka halaman daftar Career Event.
     */
    public function test_ac01_student_can_view_career_event_list_page(): void
    {
        $student = $this->user('mahasiswa');

        $response = $this->actingAs($student, 'web')
            ->get(route('events.index'));

        $response->assertOk()
            ->assertSee('Career Event');
    }

    /**
     * AC-01: Hanya event berstatus published yang ditampilkan ke mahasiswa.
     */
    public function test_ac01_only_published_events_are_shown_to_student(): void
    {
        $student = $this->user('mahasiswa');

        $published = CareerEvent::create([
            'title'            => 'Event Sudah Terbit',
            'slug'             => 'event-sudah-terbit',
            'category'         => 'Webinar',
            'description'      => 'Deskripsi event terbit.',
            'organizer'        => 'Panitia A',
            'start_at'         => '2026-12-01 09:00:00',
            'location'         => 'Online',
            'status'           => CareerEvent::STATUS_PUBLISHED,
            'registration_url' => 'https://example.com/register-published',
        ]);

        $draft = CareerEvent::create([
            'title'            => 'Event Masih Draf',
            'slug'             => 'event-masih-draf',
            'category'         => 'Seminar',
            'description'      => 'Deskripsi event draf.',
            'organizer'        => 'Panitia B',
            'start_at'         => '2026-12-05 09:00:00',
            'location'         => 'Offline',
            'status'           => CareerEvent::STATUS_DRAFT,
            'registration_url' => 'https://example.com/register-draft',
        ]);

        $response = $this->actingAs($student, 'web')
            ->get(route('events.index'));

        $response->assertOk()
            ->assertSee('Event Sudah Terbit')
            ->assertDontSee('Event Masih Draf');
    }

    /**
     * AC-01: Halaman daftar event menampilkan pilihan kategori.
     */
    public function test_ac01_event_list_shows_category_options(): void
    {
        $student = $this->user('mahasiswa');

        $response = $this->actingAs($student, 'web')
            ->get(route('events.index'));

        $response->assertOk()
            ->assertSee('Semua Kategori')
            ->assertSee('Webinar')
            ->assertSee('Workshop');
    }

    // =========================================================
    // AC-02: Pencarian berdasarkan kata kunci
    // =========================================================

    /**
     * AC-02: Mahasiswa dapat mencari event berdasarkan kata kunci judul.
     */
    public function test_ac02_student_can_search_events_by_keyword(): void
    {
        $student = $this->user('mahasiswa');

        CareerEvent::create([
            'title'            => 'Webinar Desain Produk Digital 2026',
            'slug'             => 'webinar-desain-produk-2026',
            'category'         => 'Webinar',
            'description'      => 'Deskripsi webinar desain.',
            'organizer'        => 'Komunitas Designer',
            'start_at'         => '2026-12-10 10:00:00',
            'location'         => 'Zoom Online',
            'status'           => CareerEvent::STATUS_PUBLISHED,
            'registration_url' => 'https://example.com/webinar-desain',
        ]);

        CareerEvent::create([
            'title'            => 'Workshop Laravel Backend 2026',
            'slug'             => 'workshop-laravel-2026',
            'category'         => 'Workshop',
            'description'      => 'Workshop backend.',
            'organizer'        => 'HMSI',
            'start_at'         => '2026-12-12 13:00:00',
            'location'         => 'Lab Komputer',
            'status'           => CareerEvent::STATUS_PUBLISHED,
            'registration_url' => 'https://example.com/workshop-laravel',
        ]);

        // Pencarian cocok dengan judul
        $response = $this->actingAs($student, 'web')
            ->get(route('events.index', ['keyword' => 'Desain']));

        $response->assertOk()
            ->assertSee('Webinar Desain Produk Digital 2026')
            ->assertDontSee('Workshop Laravel Backend 2026');
    }

    /**
     * AC-02: Kata kunci pencarian tetap terisi setelah halaman dimuat ulang.
     */
    public function test_ac02_search_keyword_persists_after_submit(): void
    {
        $student = $this->user('mahasiswa');

        $response = $this->actingAs($student, 'web')
            ->get(route('events.index', ['keyword' => 'Laravel']));

        $response->assertOk()
            ->assertSee('Laravel');
    }

    /**
     * AC-02: Pencarian juga dapat dilakukan berdasarkan nama penyelenggara dan lokasi.
     */
    public function test_ac02_search_works_on_organizer_and_location(): void
    {
        $student = $this->user('mahasiswa');

        CareerEvent::create([
            'title'            => 'Event Pencarian Lokasi',
            'slug'             => 'event-pencarian-lokasi-unik',
            'category'         => 'Seminar',
            'description'      => 'Deskripsi.',
            'organizer'        => 'Penyelenggara Khusus Unik',
            'start_at'         => '2026-12-15 09:00:00',
            'location'         => 'Gedung Spesifik Unik',
            'status'           => CareerEvent::STATUS_PUBLISHED,
            'registration_url' => 'https://example.com/register',
        ]);

        // Cari berdasarkan penyelenggara
        $this->actingAs($student, 'web')
            ->get(route('events.index', ['keyword' => 'Penyelenggara Khusus Unik']))
            ->assertOk()
            ->assertSee('Event Pencarian Lokasi');

        // Cari berdasarkan lokasi
        $this->actingAs($student, 'web')
            ->get(route('events.index', ['keyword' => 'Gedung Spesifik Unik']))
            ->assertOk()
            ->assertSee('Event Pencarian Lokasi');
    }

    // =========================================================
    // AC-03: Filter berdasarkan kategori
    // =========================================================

    /**
     * AC-03: Mahasiswa dapat memfilter event berdasarkan kategori.
     */
    public function test_ac03_student_can_filter_events_by_category(): void
    {
        $student = $this->user('mahasiswa');

        CareerEvent::create([
            'title'            => 'Webinar Spesifik Filter',
            'slug'             => 'webinar-spesifik-filter',
            'category'         => 'Webinar',
            'description'      => 'Deskripsi webinar.',
            'organizer'        => 'Panitia',
            'start_at'         => '2026-12-01 10:00:00',
            'location'         => 'Online',
            'status'           => CareerEvent::STATUS_PUBLISHED,
            'registration_url' => 'https://example.com/webinar',
        ]);

        CareerEvent::create([
            'title'            => 'Workshop Spesifik Filter',
            'slug'             => 'workshop-spesifik-filter',
            'category'         => 'Workshop',
            'description'      => 'Deskripsi workshop.',
            'organizer'        => 'Panitia',
            'start_at'         => '2026-12-02 10:00:00',
            'location'         => 'Lab',
            'status'           => CareerEvent::STATUS_PUBLISHED,
            'registration_url' => 'https://example.com/workshop',
        ]);

        // Filter Webinar saja
        $response = $this->actingAs($student, 'web')
            ->get(route('events.index', ['category' => 'Webinar']));

        $response->assertOk()
            ->assertSee('Webinar Spesifik Filter')
            ->assertDontSee('Workshop Spesifik Filter');
    }

    /**
     * AC-03: Filter kategori dan pencarian dapat digunakan bersamaan.
     */
    public function test_ac03_filter_and_search_can_be_combined(): void
    {
        $student = $this->user('mahasiswa');

        CareerEvent::create([
            'title'            => 'Webinar Data Science',
            'slug'             => 'webinar-data-science-combo',
            'category'         => 'Webinar',
            'description'      => 'Data Science webinar.',
            'organizer'        => 'GDSC',
            'start_at'         => '2026-12-05 10:00:00',
            'location'         => 'Online',
            'status'           => CareerEvent::STATUS_PUBLISHED,
            'registration_url' => 'https://example.com/data',
        ]);

        CareerEvent::create([
            'title'            => 'Seminar Data Science',
            'slug'             => 'seminar-data-science-combo',
            'category'         => 'Seminar',
            'description'      => 'Data Science seminar.',
            'organizer'        => 'FRI',
            'start_at'         => '2026-12-06 10:00:00',
            'location'         => 'Auditorium',
            'status'           => CareerEvent::STATUS_PUBLISHED,
            'registration_url' => 'https://example.com/seminar',
        ]);

        // Filter Webinar + keyword Data Science
        $response = $this->actingAs($student, 'web')
            ->get(route('events.index', ['category' => 'Webinar', 'keyword' => 'Data Science']));

        $response->assertOk()
            ->assertSee('Webinar Data Science')
            ->assertDontSee('Seminar Data Science');
    }

    // =========================================================
    // AC-04: Membuka halaman detail event
    // =========================================================

    /**
     * AC-04: Mahasiswa dapat membuka halaman detail event published.
     */
    public function test_ac04_student_can_open_published_event_detail(): void
    {
        $student = $this->user('mahasiswa');

        $event = CareerEvent::where('status', CareerEvent::STATUS_PUBLISHED)->firstOrFail();

        $response = $this->actingAs($student, 'web')
            ->get(route('events.show', $event));

        $response->assertOk()
            ->assertSee($event->title)
            ->assertSee($event->organizer);
    }

    /**
     * AC-04: Mahasiswa mendapatkan 403 jika mencoba membuka event draft.
     */
    public function test_ac04_student_cannot_view_draft_event(): void
    {
        $student = $this->user('mahasiswa');

        $draft = CareerEvent::create([
            'title'            => 'Event Draf Tersembunyi',
            'slug'             => 'event-draf-tersembunyi',
            'category'         => 'Webinar',
            'description'      => 'Deskripsi.',
            'organizer'        => 'Panitia',
            'start_at'         => '2026-12-20 09:00:00',
            'location'         => 'TBD',
            'status'           => CareerEvent::STATUS_DRAFT,
            'registration_url' => 'https://example.com/draft',
        ]);

        $this->actingAs($student, 'web')
            ->get(route('events.show', $draft))
            ->assertForbidden();
    }

    /**
     * AC-04: Halaman detail event yang tidak ditemukan mengembalikan 404.
     */
    public function test_ac04_event_not_found_returns_404(): void
    {
        $student = $this->user('mahasiswa');

        $this->actingAs($student, 'web')
            ->get('/events/999999')
            ->assertNotFound();
    }

    // =========================================================
    // AC-05: Informasi detail event ditampilkan lengkap
    // =========================================================

    /**
     * AC-05: Halaman detail menampilkan semua informasi event.
     */
    public function test_ac05_event_detail_shows_all_required_information(): void
    {
        $student = $this->user('mahasiswa');

        $event = CareerEvent::create([
            'title'            => 'Event Detail Lengkap Test',
            'slug'             => 'event-detail-lengkap-test',
            'category'         => 'Career Fair',
            'description'      => 'Deskripsi lengkap kegiatan career fair ini.',
            'organizer'        => 'CDC Telkom University',
            'start_at'         => '2026-11-20 09:00:00',
            'end_at'           => '2026-11-20 17:00:00',
            'location'         => 'Auditorium Damar Lantai 2',
            'status'           => CareerEvent::STATUS_PUBLISHED,
            'registration_url' => 'https://cdc.telkomuniversity.ac.id/careerfair',
        ]);

        $response = $this->actingAs($student, 'web')
            ->get(route('events.show', $event));

        $response->assertOk()
            ->assertSee('Event Detail Lengkap Test')       // Judul
            ->assertSee('CDC Telkom University')           // Penyelenggara
            ->assertSee('Career Fair')                     // Kategori
            ->assertSee('Auditorium Damar Lantai 2')       // Lokasi
            ->assertSee('Deskripsi lengkap kegiatan career fair ini.'); // Deskripsi
    }

    /**
     * AC-05: Halaman detail menampilkan poster jika tersedia.
     */
    public function test_ac05_event_detail_displays_poster_when_available(): void
    {
        $student = $this->user('mahasiswa');

        // Simpan poster palsu di storage
        $fakeFile = UploadedFile::fake()->image('poster_test.jpg');
        $posterPath = $fakeFile->store('events', 'local');

        $event = CareerEvent::create([
            'title'            => 'Event Dengan Poster',
            'slug'             => 'event-dengan-poster-test',
            'category'         => 'Webinar',
            'description'      => 'Event dengan poster.',
            'organizer'        => 'Panitia',
            'start_at'         => '2026-12-01 10:00:00',
            'location'         => 'Online',
            'poster_path'      => $posterPath,
            'status'           => CareerEvent::STATUS_PUBLISHED,
            'registration_url' => 'https://example.com/poster-event',
        ]);

        $response = $this->actingAs($student, 'web')
            ->get(route('events.show', $event));

        // Halaman harus memuat URL poster
        $response->assertOk()
            ->assertSee(route('events.poster', $event), false);
    }

    /**
     * AC-05: Poster route mahasiswa mengembalikan gambar untuk event published.
     */
    public function test_ac05_poster_route_serves_image_for_published_event(): void
    {
        $student = $this->user('mahasiswa');

        $fakeFile = UploadedFile::fake()->image('serve_poster.jpg');
        $posterPath = $fakeFile->store('events', 'local');

        $event = CareerEvent::create([
            'title'            => 'Event Poster Serve Test',
            'slug'             => 'event-poster-serve-test',
            'category'         => 'Seminar',
            'description'      => 'Event.',
            'organizer'        => 'Panitia',
            'start_at'         => '2026-12-01 10:00:00',
            'location'         => 'Online',
            'poster_path'      => $posterPath,
            'status'           => CareerEvent::STATUS_PUBLISHED,
            'registration_url' => 'https://example.com/register',
        ]);

        $this->actingAs($student, 'web')
            ->get(route('events.poster', $event))
            ->assertOk();
    }

    /**
     * AC-05: Poster route mengembalikan 404 jika event tidak punya poster.
     */
    public function test_ac05_poster_route_returns_404_when_no_poster(): void
    {
        $student = $this->user('mahasiswa');

        $event = CareerEvent::create([
            'title'            => 'Event Tanpa Poster',
            'slug'             => 'event-tanpa-poster-test',
            'category'         => 'Webinar',
            'description'      => 'Event tanpa poster.',
            'organizer'        => 'Panitia',
            'start_at'         => '2026-12-01 10:00:00',
            'location'         => 'Online',
            'poster_path'      => null,
            'status'           => CareerEvent::STATUS_PUBLISHED,
            'registration_url' => 'https://example.com/register',
        ]);

        $this->actingAs($student, 'web')
            ->get(route('events.poster', $event))
            ->assertNotFound();
    }

    // =========================================================
    // AC-06: Tautan pendaftaran resmi eksternal
    // =========================================================

    /**
     * AC-06: Halaman detail menampilkan tautan pendaftaran menuju URL eksternal.
     */
    public function test_ac06_event_detail_shows_external_registration_link(): void
    {
        $student = $this->user('mahasiswa');

        $event = CareerEvent::create([
            'title'            => 'Event Dengan Tautan Daftar',
            'slug'             => 'event-dengan-tautan-daftar',
            'category'         => 'Webinar',
            'description'      => 'Deskripsi.',
            'organizer'        => 'Panitia',
            'start_at'         => '2026-12-01 10:00:00',
            'location'         => 'Online',
            'status'           => CareerEvent::STATUS_PUBLISHED,
            'registration_url' => 'https://external-organizer.com/register/2026',
        ]);

        $response = $this->actingAs($student, 'web')
            ->get(route('events.show', $event));

        $response->assertOk()
            // Tautan menuju URL eksternal harus tampil
            ->assertSee('https://external-organizer.com/register/2026', false)
            // Harus ada target blank dan rel noopener
            ->assertSee('target="_blank"', false)
            ->assertSee('noopener noreferrer', false);
    }

    /**
     * AC-08: Tidak ada form pendaftaran internal di halaman event mahasiswa.
     */
    public function test_ac08_no_internal_registration_form_on_event_pages(): void
    {
        $student = $this->user('mahasiswa');

        $event = CareerEvent::where('status', CareerEvent::STATUS_PUBLISHED)->firstOrFail();

        // Halaman daftar tidak ada form registrasi internal
        $indexResponse = $this->actingAs($student, 'web')
            ->get(route('events.index'));
        $indexResponse->assertOk()
            ->assertDontSee('Daftar Sekarang')
            ->assertDontSee('Formulir Pendaftaran');

        // Halaman detail tidak ada form registrasi internal
        $showResponse = $this->actingAs($student, 'web')
            ->get(route('events.show', $event));
        $showResponse->assertOk()
            ->assertDontSee('Formulir Pendaftaran')
            ->assertDontSee('Data Peserta');
    }

    // =========================================================
    // AC-07: Empty state ketika tidak ada hasil
    // =========================================================

    /**
     * AC-07: Empty state muncul ketika pencarian tidak menghasilkan event.
     */
    public function test_ac07_empty_state_shown_when_search_returns_no_results(): void
    {
        $student = $this->user('mahasiswa');

        $response = $this->actingAs($student, 'web')
            ->get(route('events.index', ['keyword' => 'katakunci-yang-pasti-tidak-ada-xyzxyz']));

        $response->assertOk()
            ->assertSee('Event tidak ditemukan')
            ->assertSee('Reset Pencarian');
    }

    /**
     * AC-07: Empty state muncul ketika filter kategori tidak menghasilkan event.
     */
    public function test_ac07_empty_state_shown_when_category_filter_returns_no_results(): void
    {
        $student = $this->user('mahasiswa');

        // Pastikan tidak ada event Kompetisi yang published (dari seeder hanya ada yang lain)
        CareerEvent::where('category', 'Kompetisi')->delete();

        $response = $this->actingAs($student, 'web')
            ->get(route('events.index', ['category' => 'Kompetisi']));

        $response->assertOk()
            ->assertSee('Event tidak ditemukan');
    }

    // =========================================================
    // Otorisasi: Mahasiswa tidak bisa akses route Admin
    // =========================================================

    /**
     * Mahasiswa tidak bisa mengakses CRUD admin event (create, store, edit, update, destroy).
     */
    public function test_student_cannot_access_admin_event_management_routes(): void
    {
        $student = $this->user('mahasiswa');

        $event = CareerEvent::where('status', CareerEvent::STATUS_PUBLISHED)->firstOrFail();

        // Create
        $this->actingAs($student, 'web')
            ->get(route('admin.events.create'))
            ->assertForbidden();

        // Store
        $this->actingAs($student, 'web')
            ->post(route('admin.events.store'), [])
            ->assertForbidden();

        // Edit
        $this->actingAs($student, 'web')
            ->get(route('admin.events.edit', $event))
            ->assertForbidden();

        // Update
        $this->actingAs($student, 'web')
            ->put(route('admin.events.update', $event), [])
            ->assertForbidden();

        // Destroy
        $this->actingAs($student, 'web')
            ->delete(route('admin.events.destroy', $event))
            ->assertForbidden();
    }

    /**
     * Admin tidak dapat mengakses halaman event mahasiswa (route events.index, events.show).
     * Route mahasiswa dilindungi oleh middleware role:mahasiswa.
     */
    public function test_admin_cannot_access_student_event_routes(): void
    {
        $admin = $this->user('admin');

        $event = CareerEvent::where('status', CareerEvent::STATUS_PUBLISHED)->firstOrFail();

        $this->actingAs($admin, 'web')
            ->get(route('events.index'))
            ->assertForbidden();

        $this->actingAs($admin, 'web')
            ->get(route('events.show', $event))
            ->assertForbidden();
    }

    /**
     * Pengguna yang belum login tidak dapat mengakses halaman event.
     */
    public function test_unauthenticated_user_cannot_access_event_pages(): void
    {
        $event = CareerEvent::where('status', CareerEvent::STATUS_PUBLISHED)->firstOrFail();

        $this->get(route('events.index'))
            ->assertRedirect(route('login'));

        $this->get(route('events.show', $event))
            ->assertRedirect(route('login'));
    }
}

