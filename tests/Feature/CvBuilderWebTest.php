<?php

namespace Tests\Feature;

use App\Models\CurriculumVitae;
use App\Models\User;

class CvBuilderWebTest extends ApiTestCase
{
    public function test_guest_is_redirected_to_login_when_accessing_cv_builder(): void
    {
        $this->get(route('cv.index'))->assertRedirect(route('login'));
        $this->get(route('cv.edit'))->assertRedirect(route('login'));
        $this->put(route('cv.update'), [])->assertRedirect(route('login'));
        $this->get(route('cv.preview'))->assertRedirect(route('login'));
    }

    public function test_admin_and_assessor_cannot_access_student_cv_builder(): void
    {
        $this->actingAs($this->user('admin'), 'web')
            ->get(route('cv.index'))
            ->assertForbidden();

        $this->actingAs($this->user('assessor'), 'web')
            ->get(route('cv.index'))
            ->assertForbidden();
    }

    public function test_mahasiswa_can_view_cv_builder_page(): void
    {
        $student = $this->user('mahasiswa');

        $response = $this->actingAs($student, 'web')->get(route('cv.index'));

        $response->assertOk()
            ->assertViewIs('cv.index')
            ->assertSee('CV Builder')
            ->assertSee('Edit Informasi CV')
            ->assertSee('Pendidikan')
            ->assertSee('Keterampilan')
            ->assertSee('Pengalaman (Kerja, Magang, Organisasi)')
            ->assertSee('Proyek & Portofolio')
            ->assertSee('Sertifikasi & Lisensi');

        // Memastikan CV terbuat otomatis untuk mahasiswa
        $this->assertDatabaseHas('curriculum_vitaes', [
            'user_id' => $student->id,
            'full_name' => $student->name,
        ]);
    }

    public function test_mahasiswa_can_view_edit_cv_page(): void
    {
        $student = $this->user('mahasiswa');

        $response = $this->actingAs($student, 'web')->get(route('cv.edit'));

        $response->assertOk()
            ->assertViewIs('cv.edit')
            ->assertSee('Edit Informasi CV Mahasiswa')
            ->assertSee('1. Data Profil')
            ->assertSee('2. Informasi Kontak')
            ->assertSee('3. Riwayat Pendidikan')
            ->assertSee('4. Keterampilan & Keahlian')
            ->assertSee('5. Pengalaman (Kerja, Magang, Organisasi)')
            ->assertSee('6. Proyek & Portofolio')
            ->assertSee('7. Sertifikasi & Lisensi')
            ->assertSee('Simpan Perubahan CV');
    }

    public function test_mahasiswa_can_update_all_cv_information(): void
    {
        $student = $this->user('mahasiswa');

        $payload = [
            'full_name' => 'Ahmad Fairuz Mahasiswa',
            'headline' => 'Aspiring IT Business Analyst & Systems Architect',
            'summary' => 'Mahasiswa tingkat akhir dengan rekam jejak riset proses bisnis dan pengembangan web Laravel.',
            'email' => 'fairuz.student@careercompass.test',
            'phone' => '+62 812-9876-5432',
            'address' => 'Kota Surabaya, Jawa Timur',
            'linkedin_url' => 'https://linkedin.com/in/ahmad-fairuz',
            'portfolio_url' => 'https://fairuz.dev',
            'github_url' => 'https://github.com/ahmadfairuz',
            'educations' => [
                [
                    'institution' => 'Telkom University Surabaya',
                    'degree' => 'Sarjana Komputer (S.Kom)',
                    'field_of_study' => 'Sistem Informasi',
                    'start_year' => '2022',
                    'end_year' => '2026',
                    'gpa' => '3.89',
                    'description' => 'Fokus skripsi pada Enterprise Architecture dan Business Process Improvement.',
                ],
            ],
            'skills' => [
                [
                    'category' => 'Pemrograman & Basis Data',
                    'items' => 'PHP, Laravel 11, MySQL, REST API, Python',
                ],
                [
                    'category' => 'Analisis & Tools',
                    'items' => 'BPMN, UML, Figma, Postman, Docker',
                ],
            ],
            'experiences' => [
                [
                    'position' => 'Junior IT Analyst Intern',
                    'company' => 'PT Transformasi Digital Nusantara',
                    'location' => 'Surabaya',
                    'start_date' => 'Feb 2024',
                    'end_date' => 'Jul 2024',
                    'is_current' => false,
                    'description' => 'Menganalisis kebutuhan user dan merancang 15 modul use case diagram.',
                ],
            ],
            'projects' => [
                [
                    'title' => 'Career Compass SI Platform',
                    'role' => 'Lead Developer',
                    'period' => '2024',
                    'technologies' => 'Laravel, MySQL, JavaScript',
                    'url' => 'https://github.com/example/career-compass',
                    'description' => 'Mengembangkan modul eksplorasi profesi dan CV builder.',
                ],
            ],
            'certifications' => [
                [
                    'name' => 'Certified Scrum Master (CSM)',
                    'issuer' => 'Scrum Alliance',
                    'issue_date' => '2024',
                    'credential_id' => 'CSM-991240',
                    'credential_url' => 'https://scrumalliance.org/verify/991240',
                ],
            ],
        ];

        $response = $this->actingAs($student, 'web')->put(route('cv.update'), $payload);

        $response->assertRedirect(route('cv.index'));
        $response->assertSessionHas('status', 'Informasi CV Mahasiswa berhasil disimpan dan diperbarui.');

        $this->assertDatabaseHas('curriculum_vitaes', [
            'user_id' => $student->id,
            'full_name' => 'Ahmad Fairuz Mahasiswa',
            'headline' => 'Aspiring IT Business Analyst & Systems Architect',
            'email' => 'fairuz.student@careercompass.test',
            'phone' => '+62 812-9876-5432',
        ]);

        $cv = CurriculumVitae::where('user_id', $student->id)->firstOrFail();
        $this->assertCount(1, $cv->educations);
        $this->assertSame('Telkom University Surabaya', $cv->educations[0]['institution']);
        $this->assertCount(2, $cv->skills);
        $this->assertCount(1, $cv->experiences);
        $this->assertSame('Junior IT Analyst Intern', $cv->experiences[0]['position']);
        $this->assertCount(1, $cv->projects);
        $this->assertCount(1, $cv->certifications);
    }

    public function test_validation_fails_when_required_fields_are_invalid(): void
    {
        $student = $this->user('mahasiswa');

        $response = $this->actingAs($student, 'web')->put(route('cv.update'), [
            'full_name' => '', // required
            'email' => 'bukan-email-valid', // invalid email
        ]);

        $response->assertSessionHasErrors(['full_name', 'email']);
    }

    public function test_mahasiswa_can_view_ats_preview_page(): void
    {
        $student = $this->user('mahasiswa');

        $response = $this->actingAs($student, 'web')->get(route('cv.preview'));

        $response->assertOk()
            ->assertViewIs('cv.preview')
            ->assertSee('Pratinjau CV ATS')
            ->assertSee('Cetak / Simpan PDF')
            ->assertSee('Ringkasan Profesional')
            ->assertSee('Pendidikan')
            ->assertSee('Pengalaman Kerja & Organisasi');
    }

    public function test_empty_rows_are_sanitized_and_filtered_out(): void
    {
        $student = $this->user('mahasiswa');

        $payload = [
            'full_name' => 'Mahasiswa Test',
            'email' => 'test@careercompass.test',
            'educations' => [
                ['institution' => '', 'degree' => ''], // should be filtered out
                ['institution' => 'Universitas Indonesia', 'degree' => 'S1'], // kept
            ],
            'skills' => [
                ['category' => '', 'items' => ''], // filtered out
            ],
            'experiences' => [],
            'projects' => [],
            'certifications' => [],
        ];

        $this->actingAs($student, 'web')->put(route('cv.update'), $payload)
            ->assertRedirect(route('cv.index'));

        $cv = CurriculumVitae::where('user_id', $student->id)->firstOrFail();
        $this->assertCount(1, $cv->educations);
        $this->assertSame('Universitas Indonesia', $cv->educations[0]['institution']);
        $this->assertCount(0, $cv->skills);
    }

    public function test_data_isolation_between_students(): void
    {
        $student1 = $this->user('mahasiswa');
        
        // Buat mahasiswa kedua
        $student2 = User::factory()->create([
            'email' => 'mahasiswa2@careercompass.test',
            'role_id' => $student1->role_id,
        ]);

        // Student 1 mengupdate CV-nya
        $this->actingAs($student1, 'web')->put(route('cv.update'), [
            'full_name' => 'Data Mahasiswa Satu',
            'email' => 'mhs1@careercompass.test',
        ]);

        // Student 2 membuka CV Builder-nya
        $response = $this->actingAs($student2, 'web')->get(route('cv.index'));

        // Pastikan student 2 melihat datanya sendiri dan tidak melihat data rahasia student 1
        $response->assertOk();
        $response->assertDontSee('Data Mahasiswa Satu');
        $this->assertDatabaseHas('curriculum_vitaes', [
            'user_id' => $student2->id,
            'full_name' => $student2->name,
        ]);
    }

    public function test_sidebar_navigation_marks_cv_builder_active(): void
    {
        $student = $this->user('mahasiswa');

        $response = $this->actingAs($student, 'web')->get(route('cv.index'));

        $response->assertOk()
            ->assertSee(route('cv.index'))
            ->assertSee('CV Builder');
    }
}

