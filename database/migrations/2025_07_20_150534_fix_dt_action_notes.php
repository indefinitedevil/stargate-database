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
        Schema::table('downtime_actions', function (Blueprint $table) {
            $table->string('notes', 2048)->nullable()->change();
            $table->string('response', 2048)->nullable()->change();
        });

        DB::table('downtime_actions')->where('notes', '')->update(['notes' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse
    }
};
