<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->call([RoleSeeder::class, CareerCatalogSeeder::class]);
            if (app()->environment(['local', 'testing'])) {
                $this->call(DemoUserSeeder::class);
            }
        });
    }
}
