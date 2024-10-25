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
        Schema::table('background_feat', function (Blueprint $table) {
            $table->unique(['background_id', 'feat_id']);
        });
        Schema::table('background_skill', function (Blueprint $table) {
            $table->unique(['background_id', 'skill_id']);
        });
        Schema::table('feat_skill', function (Blueprint $table) {
            $table->unique(['skill_id', 'feat_id']);
        });
        Schema::table('skill_discounts', function (Blueprint $table) {
            $table->unique(['discounted_skill', 'discounting_skill']);
        });
        Schema::table('skill_prereqs', function (Blueprint $table) {
            $table->unique(['skill_id', 'prereq_id']);
        });
        Schema::table('skill_lockouts', function (Blueprint $table) {
            $table->unique(['skill_id', 'lockout_id']);
        });
        Schema::table('character_skill_skill_specialty', function (Blueprint $table) {
            $table->unique(['character_skill_id', 'skill_specialty_id'], 'character_skill_specialty_unique');
        });
        Schema::table('skill_specialty', function (Blueprint $table) {
            $table->unique(['name', 'specialty_type_id']);
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
