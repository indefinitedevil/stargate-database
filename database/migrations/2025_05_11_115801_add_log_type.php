<?php

use App\Models\LogType;
use Database\Seeders\SkillSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $logType = new LogType();
        $logType->id = 4;
        $logType->name = 'System';
        $logType->save();

        $seeder = new SkillSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
