<?php

namespace Database\Seeders;

use App\Models\ActionPlanTemplate;
use Illuminate\Database\Seeder;

class ActionPlanTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'title' => 'Mengikuti Kursus Online',
                'description' => 'Mengikuti kursus online yang relevan dengan kompetensi karier untuk memperdalam pengetahuan teoretis dan praktis.',
                'is_active' => true,
            ],
            [
                'title' => 'Membangun Project Portofolio',
                'description' => 'Membuat project untuk menerapkan kompetensi yang sedang dikembangkan dan dapat ditunjukkan kepada calon perekrut.',
                'is_active' => true,
            ],
            [
                'title' => 'Mengikuti Workshop',
                'description' => 'Mengikuti workshop yang relevan dengan bidang pengembangan kompetensi bersama praktisi industri.',
                'is_active' => true,
            ],
            [
                'title' => 'Belajar Teknologi Baru',
                'description' => 'Mempelajari teknologi atau tools yang relevan dengan target pengembangan diri dan kebutuhan industri saat ini.',
                'is_active' => true,
            ],
            [
                'title' => 'Mengikuti Sertifikasi',
                'description' => 'Mengikuti proses pembelajaran dan persiapan ujian sertifikasi kompetensi profesional yang relevan.',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            ActionPlanTemplate::updateOrCreate(
                ['title' => $template['title']],
                $template
            );
        }
    }
}
