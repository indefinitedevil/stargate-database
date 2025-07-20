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
        Schema::table('downtimes', function (Blueprint $table) {
            $table->tinyInteger('development_actions')->default(3);
            $table->tinyInteger('research_actions')->default(3);
            $table->tinyInteger('other_actions')->default(1);
        });

        Schema::create('action_types', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('downtime_missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('downtime_id')->constrained();
            $table->string('name');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('research_projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('research_project_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_project_id')->constrained();
            $table->foreignId('skill_id')->constrained();
            $table->integer('months');
            $table->timestamps();
        });

        Schema::create('downtime_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('downtime_id')->constrained();
            $table->foreignId('action_type_id')->constrained();
            $table->foreignId('character_id')->constrained();
            $table->foreignId('character_skill_id')->nullable()->constrained();
            $table->foreignId('downtime_mission_id')->nullable()->constrained();
            $table->foreignId('research_project_id')->nullable()->constrained();
            $table->text('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
