<?php

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
        Schema::create('research_project_skill_specialty', function (Blueprint $table) {
            $table->foreignId('research_project_id')
                ->constrained('research_projects')
                ->onDelete('cascade');
            $table->foreignId('skill_specialty_id')
                ->constrained('skill_specialties')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_project_skill_specialty');
    }
};
