<?php

use App\Models\LogType;
use Database\Seeders\SkillSeeder;
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
        Schema::table('log_types', function (Blueprint $table) {
            $table->timestamps();
        });
        $logType = new LogType();
        $logType->id = 4;
        $logType->name = 'System';
        $logType->save();

        Schema::table('skills', function (Blueprint $table) {
            $table->tinyInteger('repeatable')->change()->unsigned();
        });
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
