<?php

use App\Models\ActionType;
use App\Models\CharacterLog;
use App\Models\CharacterSkill;
use App\Models\DowntimeAction;
use App\Models\LogType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $skillUpdate = [
            46 => [95, 96, 97], // Biology
            48 => [105], // Chemistry
            49 => [98, 99], // Computers
            50 => [100, 101], // Engineering
            55 => [102, 103], // Maths
            56 => [105], // Physics
        ];
        foreach ($skillUpdate as $original_skill_id => $updates) {
            $characterSkills = CharacterSkill::where('skill_id', $original_skill_id)
                ->where('removed', false)
                ->get();
            foreach ($characterSkills as $characterSkill) {
                $discountUsedBy = $discountSubSkills = null;
                if ($characterSkill->discount_used) {
                    $discountUsedBy = $characterSkill->discountUsedBy;
                    $discountSubSkills = $discountUsedBy->skill->subSkills->pluck('id')->toArray();
                    $characterSkill->discount_used = false;
                    $characterSkill->discount_used_by = null;
                }
                $characterSkill->removed = true;
                $characterSkill->save();

                $characterLog = new CharacterLog();
                $characterLog->fill([
                    'character_id' => $characterSkill->character_id,
                    'character_skill_id' => $characterSkill->id,
                    'log_type_id' => LogType::SYSTEM,
                    'locked' => true,
                    'amount_trained' => 0,
                    'completed' => $characterSkill->completed,
                    'notes' => __('Removed due to rules 2.0 update.')
                ]);
                $characterLog->save();

                $researchActions = DowntimeAction::where('action_type_id', ActionType::ACTION_RESEARCHING)
                    ->where('character_skill_id', $characterSkill->id)
                    ->join('downtimes', 'downtimes.id', '=', 'downtime_actions.downtime_id')
                    ->where('downtimes.processed', false)
                    ->select('downtime_actions.*')
                    ->get();

                foreach ($updates as $updated_skill_id) {
                    $newCharacterSkill = new CharacterSkill();
                    $newCharacterSkill->character_id = $characterSkill->character_id;
                    $newCharacterSkill->skill_id = $updated_skill_id;
                    $newCharacterSkill->completed = $characterSkill->completed;
                    if ($discountSubSkills && in_array($updated_skill_id, $discountSubSkills)) {
                        $newCharacterSkill->discount_used = true;
                        $newCharacterSkill->discount_used_by = $discountUsedBy->id;
                        $discountUsedBy = $discountSubSkills = null;
                    }
                    $newCharacterSkill->save();
                    if (!empty($researchActions)) {
                        foreach ($researchActions as $researchAction) {
                            $researchAction->character_skill_id = $newCharacterSkill->id;
                            $researchAction->save();
                        }
                        $researchActions = null;
                    }

                    $characterLog = new CharacterLog();
                    $characterLog->fill([
                        'character_id' => $characterSkill->character_id,
                        'character_skill_id' => $newCharacterSkill->id,
                        'log_type_id' => LogType::SYSTEM,
                        'locked' => true,
                        'amount_trained' => 0,
                        'completed' => $characterSkill->completed,
                        'notes' => __('Replaced :old with :new due to rules 2.0 update.', ['old' => str_replace(' (removed)', '', $characterSkill->skill->name), 'new' => $newCharacterSkill->skill->name])
                    ]);
                    $characterLog->save();
                }
            }

            $downtimeActions = DowntimeAction::whereIn('action_type_id', [ActionType::ACTION_TRAINING, ActionType::ACTION_TEACHING])
                ->join('downtimes', 'downtimes.id', '=', 'downtime_actions.downtime_id')
                ->where('downtimes.processed', false)
                ->join('character_skills', 'character_skills.id', '=', 'downtime_actions.character_skill_id')
                ->where('skill_id', $original_skill_id)
                ->select('downtime_actions.*')
                ->get();
            foreach ($downtimeActions as $downtimeAction) {
                $downtimeAction->delete();
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
