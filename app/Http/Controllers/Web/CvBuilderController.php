<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCurriculumVitaeRequest;
use App\Models\CurriculumVitae;
use App\Models\User;
use App\Services\GenerateCvPdf;
use App\Support\AppLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CvBuilderController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user('web');
        $cv = $this->getOrCreateCv($user);

        return view('cv.index', [
            ...AppLayout::data($user),
            'cv' => $cv,
        ]);
    }

    public function edit(Request $request): View
    {
        $user = $request->user('web');
        $cv = $this->getOrCreateCv($user);

        return view('cv.edit', [
            ...AppLayout::data($user),
            'cv' => $cv,
        ]);
    }

    public function update(UpdateCurriculumVitaeRequest $request): RedirectResponse
    {
        $user = $request->user('web');
        $cv = $this->getOrCreateCv($user);

        $cv->update($request->sanitizedData());

        return redirect()->route('cv.index')
            ->with('status', 'Informasi CV Mahasiswa berhasil disimpan dan diperbarui.');
    }

    public function preview(Request $request): View
    {
        $user = $request->user('web');
        $cv = $this->getOrCreateCv($user);
        $template = $request->query('template', $cv->template ?: 'ats-classic');

        return view('cv.preview', [
            ...AppLayout::data($user),
            'cv' => $cv,
            'template' => $template,
        ]);
    }

    public function download(Request $request, GenerateCvPdf $pdfService): Response
    {
        $user = $request->user('web');
        $cv = $this->getOrCreateCv($user);
        $template = $request->query('template', $cv->template ?: 'ats-classic');

        $pdfContent = $pdfService->generate($cv, $template);
        $slug = Str::slug($cv->full_name, '_') ?: 'mahasiswa';
        $filename = "CV_{$slug}.pdf";

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => strlen($pdfContent),
            'Cache-Control' => 'no-cache, private',
        ]);
    }

    protected function getOrCreateCv(User $user): CurriculumVitae
    {
        if ($user->curriculumVitae) {
            return $user->curriculumVitae;
        }

        return $user->curriculumVitae()->create([
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '+62 812-3456-7890',
            'headline' => 'Mahasiswa Sistem Informasi',
            'summary' => 'Mahasiswa Sistem Informasi yang memiliki ketertarikan tinggi dalam analisis proses bisnis, pengembangan perangkat lunak, dan manajemen data. Berorientasi pada pencapaian hasil dan siap berkontribusi dalam lingkungan profesional.',
            'address' => 'Surabaya, Jawa Timur',
            'linkedin_url' => 'https://linkedin.com/in/' . strtolower(str_replace(' ', '', $user->name)),
            'portfolio_url' => '',
            'github_url' => 'https://github.com/' . strtolower(str_replace(' ', '', $user->name)),
            'educations' => [
                [
                    'institution' => 'Institut Teknologi Telkom Surabaya',
                    'degree' => 'Sarjana (S1)',
                    'field_of_study' => 'Sistem Informasi',
                    'start_year' => '2022',
                    'end_year' => '2026',
                    'gpa' => '3.82',
                    'description' => 'Fokus pada Enterprise Systems, Analisis & Perancangan Sistem Informasi, dan Manajemen Proyek TI.',
                ],
            ],
            'skills' => [
                [
                    'category' => 'Keahlian Teknis & Pemrograman',
                    'items' => 'PHP (Laravel), MySQL, Python, HTML/CSS, Git, RESTful API',
                ],
                [
                    'category' => 'Tools & Desain',
                    'items' => 'Figma, Postman, Visual Studio Code, Draw.io, Trello',
                ],
                [
                    'category' => 'Soft Skills',
                    'items' => 'Problem Solving, Kerja Sama Tim, Komunikasi Efektif, Manajemen Waktu',
                ],
            ],
            'experiences' => [
                [
                    'position' => 'Staff Riset dan Pengembangan Teknologi',
                    'company' => 'Himpunan Mahasiswa Sistem Informasi',
                    'location' => 'Surabaya',
                    'start_date' => 'Agu 2023',
                    'end_date' => 'Jul 2024',
                    'is_current' => false,
                    'description' => 'Mengkoordinasikan workshop teknologi, mengelola platform sistem informasi kegiatan organisasi, serta menyusun dokumentasi teknis kegiatan kemahasiswaan.',
                ],
            ],
            'projects' => [
                [
                    'title' => 'Career Compass Platform',
                    'role' => 'System Analyst & Full-Stack Developer',
                    'period' => '2024',
                    'url' => 'https://github.com/example/career-compass',
                    'technologies' => 'Laravel, MySQL, JavaScript, Blade',
                    'description' => 'Membangun platform terpadu persiapan karier mahasiswa dengan fitur eksplorasi profesi, action plan, dan CV builder interaktif.',
                ],
            ],
            'certifications' => [
                [
                    'name' => 'Foundations of Project Management',
                    'issuer' => 'Google / Coursera',
                    'issue_date' => '2024',
                    'expiration_date' => '',
                    'credential_id' => 'COURSERA-PM-9821',
                    'credential_url' => 'https://coursera.org/verify/example',
                ],
            ],
            'template' => 'ats-classic',
        ]);
    }
}

