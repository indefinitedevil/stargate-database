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
        Schema::create('backgrounds', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->text('description');
            $table->smallInteger('body')->default(10);
            $table->smallInteger('vigor')->default(10);
            $table->smallInteger('months')->default(36);
            $table->timestamps();
        });
        Schema::create('feats', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->text('description');
            $table->boolean('per_event')->default(false);
            $table->timestamps();
        });
        Schema::create('skill_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->smallInteger('cost');
            $table->boolean('scaling');
            $table->timestamps();
        });
        Schema::create('specialty_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->timestamps();
        });
        Schema::create('card_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->timestamps();
        });
        Schema::create('discount_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
        });
        Schema::create('log_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
        });
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
        });
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->text('description');
            $table->foreignId('skill_category_id')->constrained();
            $table->boolean('upkeep')->default(false);
            $table->smallInteger('cost')->default(0);
            $table->smallInteger('specialties')->default(0);
            $table->foreignId('specialty_type_id')->nullable()->constrained();
            $table->boolean('repeatable')->default(false);
            $table->smallInteger('body')->default(0);
            $table->smallInteger('vigor')->default(0);
            $table->timestamps();
        });
        Schema::create('skill_specialty', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->foreignId('specialty_type_id')->constrained();
            $table->timestamps();
        });
        Schema::create('card_type_skill', function (Blueprint $table) {
            $table->foreignId('card_type_id')->constrained();
            $table->foreignId('skill_id')->constrained();
            $table->smallInteger('number');
            $table->boolean('total')->default(true);
            $table->timestamps();
        });
        Schema::create('background_feat', function (Blueprint $table) {
            $table->foreignId('background_id')->constrained();
            $table->foreignId('feat_id')->constrained();
            $table->timestamps();
        });
        Schema::create('background_skill', function (Blueprint $table) {
            $table->foreignId('background_id')->constrained();
            $table->foreignId('skill_id')->constrained();
            $table->timestamps();
        });
        Schema::create('feat_skill', function (Blueprint $table) {
            $table->foreignId('skill_id')->constrained();
            $table->foreignId('feat_id')->constrained();
            $table->timestamps();
        });
        Schema::create('skill_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('discounted_skill');
            $table->unsignedBigInteger('discounting_skill');
            $table->smallInteger('discount');
            $table->timestamps();
            $table->foreign('discounted_skill')->references('id')->on('skills');
            $table->foreign('discounting_skill')->references('id')->on('skills');
        });
        Schema::create('skill_prereqs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('skill_id');
            $table->unsignedBigInteger('prereq_id');
            $table->boolean('always_required')->default(false);
            $table->timestamps();
            $table->foreign('skill_id')->references('id')->on('skills');
            $table->foreign('prereq_id')->references('id')->on('skills');
        });
        Schema::create('skill_lockouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('skill_id');
            $table->unsignedBigInteger('lockout_id');
            $table->timestamps();
            $table->foreign('skill_id')->references('id')->on('skills');
            $table->foreign('lockout_id')->references('id')->on('skills');
        });
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name', 64);
            $table->foreignId('background_id')->constrained();
            $table->text('history');
            $table->text('plot_notes');
            $table->foreignId('status_id');
            $table->timestamps();
        });
        Schema::create('character_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained();
            $table->foreignId('skill_id')->constrained();
            $table->boolean('completed')->default(false);
            $table->boolean('discount_used')->default(false);
            $table->unsignedBigInteger('discount_used_by')->nullable();
            $table->timestamps();
            $table->foreign('discount_used_by')->references('id')->on('character_skills');
        });
        Schema::create('character_skill_skill_specialty', function (Blueprint $table) {
            $table->foreignId('character_skill_id')->constrained();
            $table->unsignedBigInteger('skill_specialty_id');
            $table->timestamps();
            $table->foreign('skill_specialty_id')->references('id')->on('skill_specialty')->cascadeOnDelete();
        });
        Schema::create('character_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained();
            $table->foreignId('log_type_id')->constrained();
            $table->foreignId('character_skill_id')->constrained();
            $table->smallInteger('amount_trained')->default(0);
            $table->string('notes', 255)->default('');
            $table->boolean('locked')->default(false);
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->timestamps();
            $table->foreign('teacher_id')->references('id')->on('characters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('database');
    }
};
