<?php

namespace Database\Seeders;

use App\Models\CareerCategory;
use App\Models\Competency;
use App\Models\Profession;
use Illuminate\Database\Seeder;

class CareerCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Software Development' => 'Pengembangan dan pemeliharaan perangkat lunak untuk kebutuhan organisasi.',
            'Data & Analytics' => 'Pengolahan dan analisis data untuk mendukung keputusan berbasis bukti.',
            'Business & Systems Analysis' => 'Analisis proses bisnis dan kebutuhan sistem informasi.',
            'Product & Design' => 'Perancangan pengalaman pengguna dan produk digital.',
        ];
        foreach ($categories as $name => $description) {
            CareerCategory::firstOrCreate(['name' => $name], ['description' => $description]);
        }

        $competencies = [
            'Programming' => 'Menerapkan logika, struktur data, dan praktik pemrograman yang dapat dipelihara.',
            'SQL' => 'Menyusun kueri dan memahami pemodelan basis data relasional.',
            'Git' => 'Mengelola versi kode dan berkolaborasi melalui code review.',
            'Software Testing' => 'Merancang dan menjalankan pengujian perangkat lunak.',
            'REST API' => 'Merancang integrasi layanan melalui HTTP dan JSON.',
            'Statistics' => 'Menggunakan statistik deskriptif dan inferensial untuk membaca data.',
            'Data Visualization' => 'Menyajikan informasi melalui grafik dan dashboard yang mudah dipahami.',
            'Python' => 'Mengolah dan menganalisis data menggunakan Python.',
            'Requirements Analysis' => 'Menggali, mendokumentasikan, dan memvalidasi kebutuhan pemangku kepentingan.',
            'Business Process Modeling' => 'Memetakan proses bisnis menggunakan BPMN dan mengidentifikasi perbaikan.',
            'Communication' => 'Menyampaikan temuan dan berkolaborasi dengan pemangku kepentingan.',
            'User Research' => 'Menggali kebutuhan pengguna melalui wawancara dan studi penggunaan.',
            'Prototyping' => 'Membuat wireframe dan prototipe interaktif untuk menguji rancangan.',
            'Usability Testing' => 'Menguji kemudahan penggunaan dan mengusulkan perbaikan desain.',
            'UML' => 'Memodelkan struktur serta perilaku sistem menggunakan diagram UML.',
            'System Design' => 'Merancang komponen, aliran data, dan integrasi sistem informasi.',
        ];
        foreach ($competencies as $name => $description) {
            Competency::firstOrCreate(['name' => $name], ['description' => $description]);
        }

        $professions = [
            [
                'name' => 'Software Engineer',
                'category' => 'Software Development',
                'description' => 'Merancang, membangun, menguji, dan memelihara aplikasi untuk menyelesaikan kebutuhan pengguna dan organisasi. Bekerja bersama analis, desainer, serta tim operasional untuk menghasilkan perangkat lunak yang andal.',
                'responsibilities' => ['Menerjemahkan kebutuhan menjadi rancangan teknis.', 'Mengembangkan aplikasi dan REST API.', 'Menulis pengujian dan melakukan code review.', 'Memperbaiki bug serta memelihara kinerja aplikasi.'],
                'competencies' => ['Programming', 'SQL', 'Git', 'Software Testing', 'REST API'],
            ],
            [
                'name' => 'Data Analyst',
                'category' => 'Data & Analytics',
                'description' => 'Mengolah data mentah menjadi informasi yang membantu organisasi memahami kinerja dan mengambil keputusan. Memastikan kualitas data serta menyampaikan hasil analisis secara jelas.',
                'responsibilities' => ['Mengumpulkan dan membersihkan data dari berbagai sumber.', 'Menganalisis pola dan indikator kinerja.', 'Membuat visualisasi dan dashboard.', 'Menyampaikan temuan beserta keterbatasan analisis.'],
                'competencies' => ['SQL', 'Statistics', 'Data Visualization', 'Python', 'Communication'],
            ],
            [
                'name' => 'Business Analyst',
                'category' => 'Business & Systems Analysis',
                'description' => 'Menjembatani kebutuhan bisnis dan solusi teknologi informasi. Menganalisis proses yang berjalan serta menyusun kebutuhan yang dapat dipahami tim pengembangan dan pemangku kepentingan.',
                'responsibilities' => ['Melakukan wawancara untuk menggali kebutuhan bisnis.', 'Memetakan proses bisnis saat ini dan usulan perbaikan.', 'Menyusun spesifikasi kebutuhan dan acceptance criteria.', 'Mendampingi validasi solusi serta user acceptance testing.'],
                'competencies' => ['Requirements Analysis', 'Business Process Modeling', 'Communication', 'SQL'],
            ],
            [
                'name' => 'UI/UX Designer',
                'category' => 'Product & Design',
                'description' => 'Merancang pengalaman dan antarmuka produk digital berdasarkan kebutuhan pengguna. Menggunakan riset, prototipe, dan evaluasi untuk menghasilkan produk yang mudah digunakan dan konsisten.',
                'responsibilities' => ['Melakukan riset pengguna dan merangkum kebutuhan.', 'Menyusun user flow, wireframe, serta prototipe.', 'Merancang komponen antarmuka yang konsisten dan aksesibel.', 'Melakukan usability testing dan iterasi desain.'],
                'competencies' => ['User Research', 'Prototyping', 'Usability Testing', 'Communication'],
            ],
            [
                'name' => 'System Analyst',
                'category' => 'Business & Systems Analysis',
                'description' => 'Menganalisis kebutuhan sistem informasi dan merancang spesifikasi solusi yang mendukung proses organisasi. Memperhatikan integrasi, struktur data, serta kebutuhan fungsional dan nonfungsional.',
                'responsibilities' => ['Menganalisis kebutuhan fungsional dan nonfungsional.', 'Membuat model UML, rancangan basis data, serta aliran data.', 'Menyusun spesifikasi integrasi antarsistem.', 'Memvalidasi rancangan dengan pengguna dan tim pengembangan.'],
                'competencies' => ['Requirements Analysis', 'UML', 'System Design', 'SQL', 'Communication'],
            ],
        ];

        foreach ($professions as $data) {
            $profession = Profession::firstOrCreate(['name' => $data['name']], [
                'description' => $data['description'],
                'responsibilities' => $data['responsibilities'],
                'career_category_id' => CareerCategory::where('name', $data['category'])->firstOrFail()->id,
                'is_active' => true,
            ]);

            // Preserve administrator edits when the seeders run again.
            if ($profession->wasRecentlyCreated) {
                $profession->competencies()->sync(Competency::whereIn('name', $data['competencies'])->pluck('id'));
            }
        }
    }
}
