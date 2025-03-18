<?php

use App\Models\Character;
use App\Models\CharacterLog;
use App\Models\CharacterSkill;
use App\Models\LogType;
use App\Models\Status;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (Character::whereIn('status_id', [Status::APPROVED, Status::PLAYED])->get() as $character) {
            foreach ($character->background->skills as $skill) {
                $characterSkill = new CharacterSkill();
                $characterSkill->character_id = $character->id;
                $characterSkill->skill_id = $skill->id;
                $characterSkill->completed = true;
                $characterSkill->save();

                $log = new CharacterLog();
                $logData = [
                    'character_id' => $character->id,
                    'character_skill_id' => $characterSkill->id,
                    'locked' => true,
                    'amount_trained' => 0,
                    'log_type_id' => LogType::CHARACTER_CREATION,
                    'teacher_id' => null,
                ];
                $log->fill($logData);
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
