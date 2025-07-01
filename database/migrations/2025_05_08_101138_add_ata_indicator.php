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
        Schema::table('characters', function (Blueprint $table) {
            $table->tinyInteger('ata_gene')->default(-1);
            $table->boolean('ata_revealed')->default(false);
            $table->json('genetics_indicator')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn('ata_gene');
            $table->dropColumn('ata_revealed');
            $table->dropColumn('genetics_indicator');
        });
    }
};
