<?php

use App\Models\CharacterLog;
use App\Models\LogType;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $logs = CharacterLog::where('log_type_id', LogType::PLOT)
            ->whereDate('created_at', '>=', '2025-05-05')
            ->get();

        foreach ($logs as $log) {
            if (60 == $log->characterSkill->skill_id) {
                $log->characterSkill->skill_id = 90;
                $log->characterSkill->save();
                $log->body_change = 1;
                $log->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
