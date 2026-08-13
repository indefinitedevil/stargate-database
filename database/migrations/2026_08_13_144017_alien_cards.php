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
        Schema::table('card_type_skill', function (Blueprint $table) {
            if (Schema::hasColumn('card_type_skill', 'alien')) {
                return;
            }
            $table->smallInteger('alien')->default(0)->after('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('card_type_skill', function (Blueprint $table) {
            if (Schema::hasColumn('card_type_skill', 'alien')) {
                $table->dropColumn('alien');
            }
        });
    }
};
