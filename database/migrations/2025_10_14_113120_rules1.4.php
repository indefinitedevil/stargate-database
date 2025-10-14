<?php

use Database\Seeders\FeatSeeder;
use Database\Seeders\SkillSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $seeder = new SkillSeeder();
        $seeder->run();

        $seeder = new FeatSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $seeder = new SkillSeeder();
        $seeder->run();

        $seeder = new FeatSeeder();
        $seeder->run();
    }
};
