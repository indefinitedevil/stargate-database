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
        Schema::table('log_types', function (Blueprint $table) {
            $table->timestamps();
        });
        DB::table('log_types')->insert([
            ['id' => 4, 'name' => 'System', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::table('skills', function (Blueprint $table) {
            $table->tinyInteger('repeatable')->change()->unsigned();
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
