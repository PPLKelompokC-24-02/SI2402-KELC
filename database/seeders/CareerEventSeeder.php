<?php

namespace Database\Seeders;

use App\Models\CareerEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CareerEventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'Telkom University Annual Career Fair 2026',
                'category' => 'Career Fair',
                'description' => "Career Fair tahunan terbesar yang mempertemukan mahasiswa dan alumni Telkom University dengan lebih dari 50 perusahaan terkemuka di bidang teknologi, telekomunikasi, keuangan, dan industri kreatif.\n\nFasilitas kegiatan:\n- Walk-in interview dengan HRD\n- Sesi info sharing perusahaan\n- Review CV langsung di booth konsultasi karier",
                'organizer' => 'Career Development Center (CDC) Telkom University',
                'start_at' => Carbon::parse('2026-11-20 09:00:00'),
                'end_at' => Carbon::parse('2026-11-21 16:00:00'),
                'location' => 'Auditorium Gd. Damar, Kampus Jakarta & Online Expo',
                'status' => CareerEvent::STATUS_PUBLISHED,
                'registration_url' => 'https://cdc.telkomuniversity.ac.id/careerfair2026',
            ],
            [
                'title' => 'Workshop: Building Production-Ready REST API with Laravel',
                'category' => 'Workshop',
                'description' => "Workshop praktis intensif selama 3 jam untuk memperdalam keterampilan backend engineering menggunakan framework Laravel, mencakup implementasi authentication Sanctum, repository pattern, dan automated testing.\n\nInstruktur: Software Engineer Senior alumni Telkom University.",
                'organizer' => 'Himpunan Mahasiswa Sistem Informasi (HMSI)',
                'start_at' => Carbon::parse('2026-11-12 13:30:00'),
                'end_at' => Carbon::parse('2026-11-12 16:30:00'),
                'location' => 'Laboratorium Komputer Lab 204 & Zoom Meeting',
                'status' => CareerEvent::STATUS_PUBLISHED,
                'registration_url' => 'https://hmsi.telkomuniversity.ac.id/workshop-laravel',
            ],
            [
                'title' => 'Webinar: Navigating Your Career Path in Data & AI',
                'category' => 'Webinar',
                'description' => 'Bahas tuntas tren karier di bidang Data Analytics, Data Science, dan Artificial Intelligence. Ketahui keterampilan apa saja yang paling dicari industri saat ini dan bagaimana menyusun portofolio data yang memikat recruiter.',
                'organizer' => 'Google Developer Student Clubs (GDSC) Telkom University',
                'start_at' => Carbon::parse('2026-11-15 10:00:00'),
                'end_at' => Carbon::parse('2026-11-15 12:00:00'),
                'location' => 'Live Streaming YouTube & Zoom Webinar',
                'status' => CareerEvent::STATUS_PUBLISHED,
                'registration_url' => 'https://gdsc.community.dev/events/telkom-university-data-ai',
            ],
            [
                'title' => 'Seminar Nasional: Masa Depan Karier Lulusan Sistem Informasi',
                'category' => 'Seminar',
                'description' => 'Seminar tatap muka interaktif bersama para praktisi industri IT dan alumni sukses membahas lanskap pekerjaan masa depan serta kesiapan kompetensi lulusan program studi Sistem Informasi.',
                'organizer' => 'Fakultas Rekayasa Industri & CDC Telkom University',
                'start_at' => Carbon::parse('2026-11-25 08:30:00'),
                'end_at' => Carbon::parse('2026-11-25 12:00:00'),
                'location' => 'Auditorium Utama Gedung Damar Lantai 2',
                'status' => CareerEvent::STATUS_PUBLISHED,
                'registration_url' => 'https://telkomuniversity.ac.id/seminar-nasional-si-2026',
            ],
            [
                'title' => 'Pelatihan & Sertifikasi Scrum Master Foundation (Draf)',
                'category' => 'Sertifikasi',
                'description' => 'Program sertifikasi profesional Agile Scrum Master bagi mahasiswa tingkat akhir yang tertarik dengan peran Project Manager, Product Owner, atau Scrum Master di industri IT.',
                'organizer' => 'Program Studi Sistem Informasi Telkom University',
                'start_at' => Carbon::parse('2026-12-05 08:30:00'),
                'end_at' => Carbon::parse('2026-12-06 17:00:00'),
                'location' => 'Ruang Seminar Lantai 3 Gedung A',
                'status' => CareerEvent::STATUS_DRAFT,
                'registration_url' => 'https://telkomuniversity.ac.id/sertifikasi-scrum-2026',
            ],
        ];

        foreach ($events as $item) {
            $item['slug'] = CareerEvent::generateUniqueSlug($item['title']);
            CareerEvent::updateOrCreate(['slug' => $item['slug']], $item);
        }
    }
}
