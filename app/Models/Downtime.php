<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

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
 * @property Collection $actions
 * @property Collection $missions
 * @property Collection $trainingCourses
 * @property Event $event
 * @property int $event_id
 */
class Downtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'development_actions',
        'research_actions',
        'other_actions',
        'event_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    private $open = null;

    public function actions(): HasMany
    {
        return $this->hasMany(DowntimeAction::class);
    }

    public function missions(): HasMany
    {
        return $this->hasMany(DowntimeMission::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function isOpen(): bool
    {
        if ($this->open === null) {
            $this->open = $this->start_time < now() && $this->end_time > now();
        }
        return $this->open;
    }

    public function getResearchProjects(): Collection
    {
        return ResearchProject::all();
    }

    public function getTrainingCoursesAttribute(): HasMany
    {
        return $this->actions()->where('action_type_id', ActionType::TEACHING)->where('downtime_id', $this->id);
    }

    public function getCharacters(): Collection
    {
        $characters = [];
        foreach ($this->actions as $action) {
            $characters[$action->character_id] = $action->character;
        }
        return collect($characters);
    }
}
