<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            if (Schema::hasColumn('skills', 'per_restore')) {
                return;
            }
            $table->unsignedTinyInteger('per_restore')->default(0)->after('vigor');
        });
        Schema::table('feats', function (Blueprint $table) {
            if (Schema::hasColumn('feats', 'per_restore')) {
                return;
            }
            $table->unsignedTinyInteger('per_restore')->default(0)->after('per_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('skills', 'per_restore')) {
            Schema::table('skills', function (Blueprint $table) {
                $table->dropColumn('per_restore');
            });
        }
        if (Schema::hasColumn('feats', 'per_restore')) {
            Schema::table('feats', function (Blueprint $table) {
                $table->dropColumn('per_restore');
            });
        }
    }
};
