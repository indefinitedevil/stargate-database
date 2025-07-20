<?php

use App\Models\Feat;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Feat::destroy(44);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
