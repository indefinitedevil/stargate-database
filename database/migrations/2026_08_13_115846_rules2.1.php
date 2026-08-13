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
        Schema::table('skill_prereqs', function (Blueprint $table) {
            if (Schema::hasColumn('skill_prereqs', 'level_required')) {
                return;
            }
            $table->smallInteger('level_required')->default(1)->after('always_required');
        });
        Schema::table('skills', function (Blueprint $table) {
            if (Schema::hasColumn('skills', 'requires_teacher')) {
                return;
            }
            $table->boolean('requires_teacher')->default(false)->after('hidden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skill_prereqs', function (Blueprint $table) {
            if (Schema::hasColumn('skill_prereqs', 'level_required')) {
                $table->dropColumn('level_required');
            }
        });
        Schema::table('skills', function (Blueprint $table) {
            if (Schema::hasColumn('skills', 'requires_teacher')) {
                $table->dropColumn('requires_teacher');
            }
        });
    }
};
