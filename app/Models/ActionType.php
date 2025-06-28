<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property int action
 * @property int type
 */
class ActionType extends Model
{
    use HasFactory;

    const TYPE_DEVELOPMENT = 1;
    const TYPE_RESEARCH = 2;
    const TYPE_MISC = 3;
    const TYPE_EXPERIMENT = 4;

    const ACTION_TRAINING = 1;
    const ACTION_TEACHING = 2;
    const ACTION_UPKEEP = 3;
    const ACTION_MISSION = 4;
    const ACTION_RESEARCHING = 5;
    const ACTION_OTHER = 7;
    const ACTION_RESEARCH_SUBJECT = 8;

    protected $fillable = [
        'action',
        'type',
        'name',
    ];

    public function downtimeActions(): HasMany
    {
        return $this->hasMany(DowntimeAction::class);
    }
}
