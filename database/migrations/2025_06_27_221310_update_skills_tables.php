<?php

use Database\Seeders\SkillSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $seeder = new SkillSeeder();
        $seeder->run();

        Schema::table('skill_training', function (Blueprint $table) {
            $table->unique(['taught_skill_id', 'trained_skill_id']);
            $table->dropForeign(['taught_skill_id']);
            $table->foreign('taught_skill_id')
                ->references('id')
                ->on('skills')
                ->onDelete('cascade');
            $table->dropForeign(['trained_skill_id']);
            $table->foreign('trained_skill_id')
                ->references('id')
                ->on('skills')
                ->onDelete('cascade');
        });
        Schema::table('skill_prereqs', function (Blueprint $table) {
            $table->dropForeign(['skill_id']);
            $table->foreign('skill_id')
                ->references('id')
                ->on('skills')
                ->onDelete('cascade');
            $table->dropForeign(['prereq_id']);
            $table->foreign('prereq_id')
                ->references('id')
                ->on('skills')
                ->onDelete('cascade');
        });
        Schema::table('skill_lockouts', function (Blueprint $table) {
            $table->dropForeign(['skill_id']);
            $table->foreign('skill_id')
                ->references('id')
                ->on('skills')
                ->onDelete('cascade');
            $table->dropForeign(['lockout_id']);
            $table->foreign('lockout_id')
                ->references('id')
                ->on('skills')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skill_training', function (Blueprint $table) {
            $table->dropForeign(['taught_skill_id']);
            $table->dropForeign(['trained_skill_id']);
            $table->dropUnique(['taught_skill_id', 'trained_skill_id']);
        });
        Schema::table('skill_prereqs', function (Blueprint $table) {
            $table->dropForeign(['skill_id']);
            $table->dropForeign(['prereq_id']);
        });
        Schema::table('skill_lockouts', function (Blueprint $table) {
            $table->dropForeign(['skill_id']);
            $table->dropForeign(['lockout_id']);
        });
    }
};
