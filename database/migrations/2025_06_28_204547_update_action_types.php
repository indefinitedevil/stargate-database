<?php

use Database\Seeders\ActionTypeSeeder;
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
        Schema::table('action_types', function (Blueprint $table) {
            $table->tinyInteger('action')->default(0);
        });

        $seeder = new ActionTypeSeeder();
        $seeder->run();

        Schema::table('action_types', function (Blueprint $table) {
            $table->unique(['action', 'type']);
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
