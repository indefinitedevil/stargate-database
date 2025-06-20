<?php

use Database\Seeders\FeatSeeder;
use Database\Seeders\SkillSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('feats', function ($table) {
            if ($table->hasColumn('print_name')) {
                return;
            }
            $table->string('print_name', 64)->nullable()->after('name');
            $table->tinyInteger('per_day')->default(0)->after('per_event');
            $table->string('cost', 16)->default('')->after('per_day');
        });

        // Add skill adjustments
        $seeder = new SkillSeeder();
        $seeder->run();

        // Add feat adjustments
        $seeder = new FeatSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feats', function ($table) {
            $table->dropColumn('print_name');
            $table->dropColumn('per_day');
            $table->dropColumn('cost');
        });
    }
};
