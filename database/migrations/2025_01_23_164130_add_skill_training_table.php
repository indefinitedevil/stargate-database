<?php

use Database\Seeders\SkillSeeder;
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
        Schema::create('skill_training', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taught_skill_id')->constrained()->references('id')->on('skills');
            $table->foreignId('trained_skill_id')->constrained()->references('id')->on('skills');
            $table->timestamps();
        });

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
