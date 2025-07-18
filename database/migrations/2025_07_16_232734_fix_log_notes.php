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

        DB::table('character_logs')->where('notes', '')->update(['notes' => null]);
        DB::table('character_logs')->where('plot_notes', '')->update(['plot_notes' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse
    }
};
