<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string name
 * @property string print_name
 * @property string description
 * @property int per_event
 * @property int per_day
 * @property string cost
 * @property Collection skills
 */
class Feat extends Model
{
    use HasFactory;

    const FLASH_OF_INSIGHT = 3;
    const BOTCH_JOB = 9;

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }

    public function getPerEvent(Character $character): int
    {
        $perEvent = 0;
        if ($this->per_event) {
            foreach ($character->trainedSkills as $trainedSkill) {
                if ($trainedSkill->skill->feats->contains($this)) {
                    $perEvent++;
                }
            }
        }
        return $perEvent;
    }

    public function getPerDay(Character $character): int
    {
        $perDay = 0;
        if ($this->per_day) {
            foreach ($character->trainedSkills as $trainedSkill) {
                if ($trainedSkill->skill->feats->contains($this)) {
                    $perDay += $this->per_day;
                }
            }
        }
        return $perDay;
    }
}
