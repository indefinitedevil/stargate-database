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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });
        Schema::create('character_team', function (Blueprint $table) {
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('position')->default(0);
            $table->primary(['team_id', 'character_id']);
        });
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });
        Schema::create('character_division', function (Blueprint $table) {
            $table->foreignId('division_id')->constrained()->cascadeOnDelete();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('position')->default(0);
            $table->primary(['division_id', 'character_id']);
        });
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignId('division_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('character_department', function (Blueprint $table) {
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('position')->default(0);
            $table->primary(['department_id', 'character_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('character_department');
        Schema::drop('departments');
        Schema::drop('character_division');
        Schema::drop('divisions');
        Schema::drop('character_team');
        Schema::drop('teams');
    }
};
