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
 * @property \DateTime $start_time
 * @property \DateTime $end_time
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

    public static function getOpenDowntime(): ?Downtime
    {
        return Downtime::where('start_time', '<', now())
            ->where('end_time', '>', now())
            ->first();
    }

    public function preprocess(): array
    {
        $taughtSkills = $trainedSkills = $downtimeMissions = $researchProjects = $upkeepMaintenance = [];
        $characters = [];
        foreach ($this->actions as $action) {
            $characters[$action->character_id] = $action->character_id;
            switch ($action->action_type_id) {
                case ActionType::TEACHING:
                    $taughtSkills[$action->characterSkill->skill_id][] = $action->character_id;
                    break;
                case ActionType::TRAINING:
                    if (empty($trainedSkills[$action->characterSkill->skill_id])) {
                        $trainedSkills[$action->characterSkill->skill_id][$action->character_id] = 0;
                    }
                    $trainedSkills[$action->characterSkill->skill_id][$action->character_id]++;
                    break;
                case ActionType::MISSION:
                    $downtimeMissions[$action->downtime_mission_id][] = $action->character_id;
                    break;
                case ActionType::RESEARCH:
                    $researchProjects[$action->research_project_id][] = $action->character_id;
                    break;
                case ActionType::UPKEEP:
                case ActionType::UPKEEP_2:
                    $upkeepMaintenance[$action->characterSkill->skill_id][] = $action->character_id;
                    break;
            }
        }
        $upkeepSkills = Skill::where('upkeep', true);
        $requiredUpkeepSkills = [];
        foreach ($upkeepSkills as $skill) {
            $relevantCharacters = [];
            $relevantCharacterSkills = $skill->characterSkills()->whereIn('character_id', $characters)->get();
            foreach ($relevantCharacterSkills as $characterSkill) {
                $relevantCharacters[$characterSkill->character_id] = $characterSkill->character_id;
            }
            $requiredUpkeepSkills[$skill->id] = $relevantCharacters;
        }
        return [
            'taughtSkills' => $taughtSkills,
            'trainedSkills' => $trainedSkills,
            'downtimeMissions' => $downtimeMissions,
            'researchProjects' => $researchProjects,
            'upkeepMaintenance' => $upkeepMaintenance,
            'requiredUpkeepSkills' => $requiredUpkeepSkills,
        ];
    }
}
