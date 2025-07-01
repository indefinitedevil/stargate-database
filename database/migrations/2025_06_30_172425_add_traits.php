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
        Schema::create('character_traits', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->text('description')->nullable();
            $table->string('icon', 32);
            $table->tinyInteger('chance')->default(0);
            $table->boolean('revealed')->default(false);
            $table->timestamps();

            $table->unique('name');
            $table->unique('icon');
        });

        Schema::create('character_character_trait', function (Blueprint $table) {
            $table->foreignId('character_id')->constrained()->onDelete('cascade');
            $table->foreignId('character_trait_id')->constrained()->onDelete('cascade');
            $table->boolean('status')->default(false);
            $table->primary(['character_id', 'character_trait_id']);
        });

        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn('ata_gene', 'ata_revealed', 'genetics_indicator');
            $table->json('traits_indicator')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_character_trait');
        Schema::dropIfExists('character_traits');

        Schema::table('characters', function (Blueprint $table) {
            $table->tinyInteger('ata_gene')->default(-1);
            $table->boolean('ata_revealed')->default(false);
            $table->json('genetics_indicator')->nullable();
            $table->dropColumn('traits_indicator');
        });
    }
};
