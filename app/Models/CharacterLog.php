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
 * @property int|null teacher_id
 * @property string notes
 * @property int downtime_id
 * @property int body_change
 * @property int vigor_change
 * @property int temp_body_change
 * @property int temp_vigor_change
 * @property Character character
 * @property int id
 * @property int character_skill_id
 * @property \DateTime created_at
 */
class CharacterLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'character_id',
        'log_type_id',
        'character_skill_id',
        'amount_trained',
        'locked',
        'teacher_id',
        'notes',
        'downtime_id',
        'body_change',
        'vigor_change',
        'temp_body_change',
        'temp_vigor_change',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function logType(): BelongsTo
    {
        return $this->belongsTo(LogType::class);
    }

    public function skill(): HasOneThrough
    {
        return $this->hasOneThrough(Skill::class, CharacterSkill::class, 'id', 'id', 'character_skill_id', 'skill_id');
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

    public function addModifier($int): string
    {
        if ($int > 0) {
            return '+' . $int;
        }

        return (string) $int;
    }
}
