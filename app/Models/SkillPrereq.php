<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int skill_id
 * @property int prereq_id
 * @property bool always_required
 * @property int levels_required
 * @property Skill skill
 * @property Skill requiredSkill
 */
class SkillPrereq extends Model
{
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function requiredSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'prereq_id');
    }
}
