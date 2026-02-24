<?php

use App\Models\CharacterLog;
use App\Models\CharacterSkill;
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
        $skillUpdate = [
            46 => [95, 96, 97], // Biology
            49 => [98, 99], // Computers
            50 => [100, 101], // Engineering
            55 => [102, 103], // Maths
        ];
        foreach ($skillUpdate as $original_skill_id => $updates) {
            $characterSkills = CharacterSkill::where('skill_id', $original_skill_id)->get();
            foreach ($characterSkills as $characterSkill) {
                $characterSkill->removed = true;
                $characterSkill->save();

                $characterLog = new CharacterLog();
                $characterLog->fill([
                    'character_id' => $characterSkill->character_id,
                    'character_skill_id' => $characterSkill->id,
                    'log_type_id' => LogType::SYSTEM,
                    'locked' => true,
                    'amount_trained' => 0,
                    'completed' => true,
                    'notes' => __('Removed due to rules 1.6 update.')
                ]);
                $characterLog->save();

                foreach ($updates as $updated_skill_id) {
                    $newCharacterSkill = new CharacterSkill();
                    $newCharacterSkill->character_id = $characterSkill->character_id;
                    $newCharacterSkill->skill_id = $updated_skill_id;
                    $newCharacterSkill->completed = true;
                    $newCharacterSkill->save();

                    $characterLog = new CharacterLog();
                    $characterLog->fill([
                        'character_id' => $characterSkill->character_id,
                        'character_skill_id' => $newCharacterSkill->id,
                        'log_type_id' => LogType::SYSTEM,
                        'locked' => true,
                        'amount_trained' => 0,
                        'completed' => true,
                        'notes' => __('Replaced :old with :new due to rules 1.6 update.', ['old' => str_replace(' (removed)', '', $characterSkill->skill->name), 'new' => $newCharacterSkill->skill->name])
                    ]);
                    $characterLog->save();
                }
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
