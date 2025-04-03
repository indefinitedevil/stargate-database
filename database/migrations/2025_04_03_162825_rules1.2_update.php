<?php

use Database\Seeders\SkillSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add inactive status
        $seeder = new TypeSeeder();
        $seeder->run();

        // Add skill adjustments
        $seeder = new SkillSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
