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
        Schema::table('skills', function (Blueprint $table) {
            if (Schema::hasColumn('skills', 'hidden')) {
                return;
            }
            $table->boolean('hidden')->default(false)->after('vigor');
        });
        Schema::table('skill_specialties', function (Blueprint $table) {
            if (Schema::hasColumn('skill_specialties', 'hidden')) {
                return;
            }
            $table->boolean('hidden')->default(false)->after('specialty_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('skills', 'hidden')) {
            Schema::table('skills', function (Blueprint $table) {
                $table->dropColumn('hidden');
            });
        }
        if (Schema::hasColumn('skill_specialties', 'hidden')) {
            Schema::table('skill_specialties', function (Blueprint $table) {
                $table->dropColumn('hidden');
            });
        }
    }
};
