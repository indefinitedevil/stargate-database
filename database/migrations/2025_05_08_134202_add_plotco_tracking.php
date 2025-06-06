<?php

use App\Models\CharacterLog;
use App\Models\LogType;
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
        Schema::table('character_logs', function (Blueprint $table) {
            $table->foreignId('user_id')->default(0);
        });
        CharacterLog::chunk(100, function ($characterLogs) {
            foreach ($characterLogs as $characterLog) {
                if (LogType::PLOT === $characterLog->log_type_id) {
                    $characterLog->user_id = 64; // Plot Coordinator ID
                } else {
                    $characterLog->user_id = $characterLog->character->user_id;
                }
                $characterLog->save();
            }
        });
        Schema::table('character_logs', function (Blueprint $table) {
            $table->foreignId('user_id')->change()->constrained();
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
