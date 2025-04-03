<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int skill_id
 * @property int prereq_id
 * @property bool always_required
 * @property Skill skill
 * @property Skill requiredSkill
 */
class SkillPrereq extends Model
{
    use HasFactory;

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function requiredSkill()
    {
        return $this->belongsTo(Skill::class, 'prereq_id');
    }
}
