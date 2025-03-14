<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $downtime_id
 * @property int $action_type_id
 * @property int $character_id
 * @property int $character_skill_id
 * @property int $downtime_mission_id
 * @property int $research_project_id
 * @property string $notes
 * @property CharacterSkill $characterSkill
 * @property Character $character
 * @property Downtime $downtime
 * @property ActionType $actionType
 */
class DowntimeAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'downtime_id',
        'action_type_id',
        'character_id',
        'character_skill_id',
        'downtime_mission_id',
        'research_project_id',
        'notes',
    ];

    public function downtime(): BelongsTo
    {
        return $this->belongsTo(Downtime::class);
    }

    public function actionType(): BelongsTo
    {
        return $this->belongsTo(ActionType::class);
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function characterSkill(): BelongsTo
    {
        return $this->belongsTo(CharacterSkill::class);
    }

    public function mission(): BelongsTo
    {
        return $this->belongsTo(DowntimeMission::class, 'downtime_mission_id');
    }

    public function researchProject(): BelongsTo
    {
        return $this->belongsTo(ResearchProject::class, 'research_project_id');
    }
}
