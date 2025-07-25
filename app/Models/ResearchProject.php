<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property int id
 * @property string name
 * @property string research_subject
 * @property string project_goals
 * @property string ooc_intent
 * @property string results
 * @property string plot_notes
 * @property int months
 * @property int status
 * @property string status_name
 * @property int visibility
 * @property string visibility_name
 * @property bool needs_volunteers
 * @property int parent_project_id
 * @property Collection|DowntimeAction[] downtimeActions
 * @property Collection|DowntimeAction[] researchActions
 * @property Collection|DowntimeAction[] subjectActions
 * @property Collection|Skill[] skills
 * @property Collection|SkillSpecialty[] skillSpecialties
 * @property SkillSpecialty[][] specialties
 * @property ResearchProject parentProject
 * @property Collection|ResearchProject[] childProjects
 * @property Collection researchers
 */
class ResearchProject extends Model
{
    use HasFactory;

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_ON_HOLD = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_ABANDONED = 5;

    const VISIBILITY_PRIVATE = 0;
    const VISIBILITY_PUBLIC = 1;
    const VISIBILITY_ARCHIVED = 2;

    protected $fillable = [
        'name',
        'research_subject',
        'project_goals',
        'ooc_intent',
        'results',
        'plot_notes',
        'months',
        'status',
        'visibility',
        'needs_volunteers',
        'parent_project_id',
    ];

    public function downtimeActions(): HasMany
    {
        return $this->hasMany(DowntimeAction::class)
            ->with(['character', 'actionType', 'downtime']);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)
            ->withPivot('months');
    }

    public function skillSpecialties(): BelongsToMany
    {
        return $this->belongsToMany(SkillSpecialty::class);
    }

    public function getSpecialtiesAttribute(): array
    {
        return once(function () {
            $specialties = [];
            foreach ($this->skillSpecialties as $specialty) {
                $specialties[$specialty->specialty_type_id][] = $specialty;
            }
            return $specialties;
        });
    }

    public function parentProject(): BelongsTo
    {
        return $this->belongsTo(ResearchProject::class, 'parent_project_id');
    }

    public function childProjects(): HasMany
    {
        return $this->hasMany(ResearchProject::class, 'parent_project_id');
    }

    public function researchActions(): HasMany
    {
        return $this->downtimeActions()
            ->where('action_type_id', ActionType::ACTION_RESEARCHING)
            ->with('character');
    }

    public function subjectActions(): HasMany
    {
        return $this->downtimeActions()
            ->where('action_type_id', ActionType::ACTION_RESEARCH_SUBJECT)
            ->with('character');
    }

    public function researchCharacters($downtimeId = 0): Collection
    {
        $cacheKey = "research_characters_{$this->id}_{$downtimeId}";
        return cache()->remember($cacheKey, 1, function () use ($downtimeId) {
            $researchCharacters = [];
            $researchActions = $this->researchActions();
            if ($downtimeId) {
                $researchActions = $researchActions->where('downtime_id', $downtimeId);
            }
            $researchActions = $researchActions->get();
            /** @var DowntimeAction $researchAction */
            foreach ($researchActions as $researchAction) {
                if (empty($researchCharacters[$researchAction->character_id])) {
                    $researchCharacters[$researchAction->character_id] = [
                        'character' => $researchAction->character,
                        'actions' => [],
                        'skills' => [],
                    ];
                }
                $researchCharacters[$researchAction->character_id]['actions'][] = $researchAction;
                if (!empty($researchAction->character_skill_id)) {
                    $researchCharacters[$researchAction->character_id]['skills'][$researchAction->characterSkill->skill->print_name ?? $researchAction->characterSkill->skill->name][] = $researchAction;
                }
            }
            foreach ($researchCharacters as $characterId => $researchCharacter) {
                $skillStrings = array_map(function ($actions, $skill) {
                    return trans_choice(':count month :skill|:count months :skill', count($actions), ['count' => count($actions), 'skill' => $skill]);
                }, $researchCharacter['skills'], array_keys($researchCharacter['skills']));
                $researchCharacters[$characterId]['skills'] = implode(', ', $skillStrings);
            }
            return collect($researchCharacters);
        });
    }

    public function getResearchersAttribute(): Collection
    {
        return once(function () {
            $researchers = [];
            foreach ($this->researchActions as $researchAction) {
                $character = $researchAction->character;
                if (!isset($researchers[$character->id])) {
                    $researchers[$character->id] = [
                        'character' => $character,
                        'actions' => [],
                        'pending_actions' => [],
                        'skills' => [],
                    ];
                }
                if ($researchAction->downtime->isOpen()) {
                    $researchers[$character->id]['pending_actions'][] = $researchAction;
                } else {
                    $researchers[$character->id]['actions'][] = $researchAction;
                }
                if (!empty($researchAction->character_skill_id)) {
                    $researchers[$character->id]['skills'][] = $researchAction->characterSkill->skill->print_name ?? $researchAction->characterSkill->skill->name;
                    $researchers[$character->id]['skills'] = array_unique($researchers[$character->id]['skills']);
                    sort($researchers[$character->id]['skills']);
                }
            }
            return collect($researchers);
        });
    }

    public function getStatusNameAttribute(): string
    {
        return self::getStatusName($this->status);
    }

    public static function getStatusName($statusId): string
    {
        return match ($statusId) {
            self::STATUS_PENDING => 'Pending approval',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_ON_HOLD => 'On Hold',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_ABANDONED => 'Abandoned',
            default => 'Unknown Status',
        };
    }

    public function getVisibilityNameAttribute(): string
    {
        return self::getVisibilityName($this->visibility);
    }

    public static function getVisibilityName($visibilityId): string
    {
        return match ($visibilityId) {
            self::VISIBILITY_PRIVATE => 'Private',
            self::VISIBILITY_PUBLIC => 'Public',
            self::VISIBILITY_ARCHIVED => 'Archived',
            default => 'Unknown Visibility',
        };
    }

    public function getViewRoute(): string
    {
        return route('research.view', ['projectId' => $this, 'projectName' => Str::slug($this->name)]);
    }

    public function skillCheck($skillId): bool
    {
        foreach ($this->researchActions as $researchAction) {
            if (!empty($researchAction->character_skill_id) && $researchAction->characterSkill->skill_id === $skillId) {
                return true;
            }
        }
        return false;
    }

    public function specialtyCheck($specialtyId): bool
    {
        foreach ($this->researchActions as $researchAction) {
            if (!empty($researchAction->character_skill_id) && $researchAction->characterSkill->allSpecialties->where('id', $specialtyId)->count() > 0) {
                return true;
            }
        }
        return false;
    }
}
