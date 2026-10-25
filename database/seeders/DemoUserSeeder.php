<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use LogicException;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new LogicException('Akun demo hanya boleh dibuat di lingkungan local/testing.');
        }

        foreach ([Role::ADMIN => 'Admin Career Compass', Role::ASSESSOR => 'Assessor Career Compass', Role::MAHASISWA => 'Mahasiswa Demo'] as $role => $name) {
            if (User::where('email', $role.'@careercompass.test')->exists()) {
                continue;
            }

            $user = new User([
                'name' => $name,
                'email' => $role.'@careercompass.test',
                'password' => config('career_compass.demo_password'),
                'nim' => $role === Role::MAHASISWA ? '1202230001' : null,
            ]);
            $user->role()->associate(Role::where('name', $role)->firstOrFail());
            $user->save();
        }
    }
}
