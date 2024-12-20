<?php

use App\Models\CharacterSkill;
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
            $table->dropForeign(['character_skill_id']);
            $table->foreign('character_skill_id')->references('id')->on('character_skills')->onDelete('cascade');
        });
        Schema::table('character_logs', function (Blueprint $table) {
            $table->dropForeignIdFor(CharacterSkill::class);
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
