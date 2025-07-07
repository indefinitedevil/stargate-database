<?php

use App\Models\Feat;
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
        $seeder = new FeatSeeder();
        $seeder->run();

        Feat::destroy(44);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
