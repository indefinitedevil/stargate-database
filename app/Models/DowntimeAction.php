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
 * @property int $skill_id
 * @property int $downtime_mission_id
 * @property int $research_project_id
 * @property string $notes
 */
class DowntimeAction extends Model
{
    use HasFactory;

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

    public function skill(): BelongsTo
    {
        return $this->belongsTo(CharacterSkill::class);
    }
}
