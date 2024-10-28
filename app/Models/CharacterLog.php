<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * @property int character_id
 * @property int log_type_id
 * @property LogType logType
 * @property Skill skill
 * @property CharacterSkill characterSkill
 * @property int skill_id
 * @property int amount_trained
 * @property bool locked
 * @property Character teacher
 */
class CharacterLog extends Model
{
    use HasFactory;

    public function logType(): BelongsTo
    {
        return $this->belongsTo(LogType::class);
    }

    public function skill(): HasOneThrough
    {
        return $this->hasOneThrough(Skill::class, CharacterSkill::class, 'id', 'id', 'skill_id', 'skill_id');
    }

    public function characterSkill(): BelongsTo
    {
        return $this->belongsTo(CharacterSkill::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Character::class, 'teacher_id');
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }
}
