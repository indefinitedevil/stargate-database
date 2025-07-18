<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('character_logs', function (Blueprint $table) {
            $table->string('notes')->nullable()->change();
            $table->string('plot_notes')->nullable()->change();
        });

        DB::query('UPDATE character_logs
            SET notes = NULL
            WHERE notes = ""');
        DB::query('UPDATE character_logs
            SET plot_notes = NULL
            WHERE plot_notes = ""');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse
    }
};
