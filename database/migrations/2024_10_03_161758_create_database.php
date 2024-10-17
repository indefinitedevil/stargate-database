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
            $table->timestamps();
        });
        Schema::create('feats', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->text('description');
            $table->boolean('per_event')->default(false);
            $table->timestamps();
        });
        Schema::create('skill_category', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->smallInteger('cost');
            $table->boolean('scaling');
            $table->timestamps();
        });
        Schema::create('specialty_type', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->timestamps();
        });
        Schema::create('card_type', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->timestamps();
        });
        Schema::create('discount_type', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->timestamps();
        });
        Schema::create('log_type', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->timestamps();
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
            $table->foreignId('card_type_id')->nullable()->constrained();
            $table->smallInteger('cards')->default(0);
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('database');
    }
};
