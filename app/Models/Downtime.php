<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $start_time
 * @property string $end_time
 * @property string $created_at
 * @property string $updated_at
 * @property int $development_actions
 * @property int $research_actions
 * @property int $other_actions
 * @property DowntimeAction[] $actions
 * @property DowntimeMission[] $missions
 */
class Downtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function actions()
    {
        return $this->hasMany(DowntimeAction::class);
    }

    public function missions()
    {
        return $this->hasMany(DowntimeMission::class);
    }

    public function isOpen(): bool
    {
        static $open = null;
        if ($open === null) {
            $open = $this->start_time < now() && $this->end_time > now();
        }
        return $open;
    }

    public function getResearchProjects() {
        return ResearchProject::all();
    }
}
