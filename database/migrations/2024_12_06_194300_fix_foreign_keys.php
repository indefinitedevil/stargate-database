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
        Schema::table('character_skill_skill_specialty', function (Blueprint $table) {
            $table->dropForeign('character_skill_skill_specialty_character_skill_id_foreign');
            $table->foreign('character_skill_id')->references('id')->on('character_skills')->onDelete('cascade');
        });
        Schema::table('character_log', function (Blueprint $table) {
            $table->dropForeignIdFor('character_skill_id');
            $table->foreign('character_skill_id')->references('id')->on('character_skills')->onDelete('cascade');
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
