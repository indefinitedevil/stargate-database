<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActionType extends Model
{
    use HasFactory;

    const DEVELOPMENT = 1;
    const RESEARCH = 2;
    const OTHER = 3;

    const TRAINING = 1;
    const TEACHING = 2;
    const UPKEEP = 3;
    const MISSION = 4;
    const RESEARCHING = 5;
    const UPKEEP_2 = 6;
    const NONE = 7;

    protected $fillable = [
        'type',
        'name',
    ];

    public function downtimeActions(): HasMany
    {
        return $this->hasMany(DowntimeAction::class);
    }
}
