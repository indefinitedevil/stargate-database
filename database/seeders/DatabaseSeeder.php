<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TypeSeeder::class,
            BackgroundSeeder::class,
            SkillSeeder::class,
            FeatSeeder::class,
            RoleSeeder::class,
            EventSeeder::class,
            TestSeeder::class,
        ]);
    }
}
