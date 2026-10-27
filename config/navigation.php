<?php

$main = [
    'title' => 'Menu Utama',
    'items' => [
        ['label' => 'Beranda', 'icon' => 'home', 'route' => 'home', 'active' => 'home'],
    ],
];

$account = [
    'title' => 'Akun',
    'items' => [
        ['label' => 'Profil', 'icon' => 'user', 'route' => 'profile.edit', 'active' => 'profile.*'],
        ['label' => 'Logout', 'icon' => 'logout', 'route' => 'logout', 'method' => 'POST'],
    ],
];

return [
    'roles' => ['mahasiswa' => 'Mahasiswa', 'admin' => 'Admin', 'assessor' => 'Assessor'],
    'menus' => [
        'mahasiswa' => [
            $main,
            [
                'title' => 'Pengembangan Karier',
                'items' => [
                    ['label' => 'Career Assessment & Academic Analysis', 'icon' => 'assessment', 'route' => 'assessment.index', 'active' => 'assessment.*'],
                    ['label' => 'Career Exploration & Career Path', 'icon' => 'compass', 'route' => 'careers.index', 'active' => 'careers.*'],
                    ['label' => 'Career Action Plan', 'icon' => 'plan', 'route' => 'action-plan.index', 'active' => 'action-plan.*'],
                    ['label' => 'Career Event', 'icon' => 'calendar', 'route' => 'events.index', 'active' => 'events.*'],
                    ['label' => 'CV Builder', 'icon' => 'document', 'route' => 'cv.index', 'active' => 'cv.*'],
                    ['label' => 'Career Progress Tracking', 'icon' => 'progress', 'route' => 'progress.index', 'active' => 'progress.*'],
                ],
            ],
            $account,
        ],
        'admin' => [
            $main,
            [
                'title' => 'Manajemen Data',
                'items' => [
                    ['label' => 'Manajemen Profesi', 'icon' => 'briefcase', 'route' => 'admin.professions.index', 'active' => 'admin.professions.*'],
                    ['label' => 'Mata Kuliah', 'icon' => 'book', 'route' => 'admin.courses.index', 'active' => 'admin.courses.*'],
                    ['label' => 'Career Path', 'icon' => 'compass', 'route' => 'admin.career-paths.index', 'active' => 'admin.career-paths.*'],
                    ['label' => 'Bank Soal Assessment', 'icon' => 'assessment', 'route' => 'admin.questions.index', 'active' => 'admin.questions.*'],
                    ['label' => 'Template Action Plan', 'icon' => 'plan', 'route' => 'admin.action-templates.index', 'active' => 'admin.action-templates.*'],
                    ['label' => 'Career Event', 'icon' => 'calendar', 'route' => 'admin.events.index', 'active' => 'admin.events.*'],
                ],
            ],
            [
                'title' => 'Administrasi',
                'items' => [
                    ['label' => 'Manajemen Pengguna', 'icon' => 'users', 'route' => 'admin.users.index', 'active' => 'admin.users.*'],
                    ['label' => 'Monitoring Assessment', 'icon' => 'monitor', 'route' => 'admin.assessments.index', 'active' => 'admin.assessments.*'],
                ],
            ],
            $account,
        ],
        'assessor' => [
            $main,
            [
                'title' => 'Penilaian Karier',
                'items' => [
                    ['label' => 'Pengajuan Assessment', 'icon' => 'inbox', 'route' => 'assessor.submissions.index', 'active' => 'assessor.submissions.index'],
                    ['label' => 'Review Assessment & Data Akademik', 'icon' => 'assessment', 'route' => 'assessor.reviews.index', 'active' => 'assessor.reviews.*'],
                    ['label' => 'Evaluasi & Rekomendasi Karier', 'icon' => 'compass', 'route' => 'assessor.evaluations.index', 'active' => 'assessor.evaluations.*'],
                    ['label' => 'Publikasi Hasil Analisis', 'icon' => 'publish', 'route' => 'assessor.publications.index', 'active' => 'assessor.publications.*'],
                    ['label' => 'Riwayat Peninjauan', 'icon' => 'history', 'route' => 'assessor.history.index', 'active' => 'assessor.history.*'],
                ],
            ],
            $account,
        ],
    ],
];
