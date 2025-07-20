<?php

namespace App\Models;

use App\Mail\DowntimeProcessed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

/**
 * @property int id
 * @property string name
 * @property \DateTime start_time
 * @property \DateTime end_time
 * @property string created_at
 * @property string updated_at
 * @property int development_actions
 * @property int research_actions
 * @property int experiment_actions
 * @property int other_actions
 * @property Collection actions
 * @property Collection missions
 * @property Collection trainingCourses
 * @property Collection researchProjects
 * @property Collection researchVolunteerProjects
 * @property Event event
 * @property int event_id
 * @property bool open
 * @property bool processed
 * @property string response
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
        'experiment_actions',
        'other_actions',
        'event_id',
        'response',
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

    public function getOpenAttribute(): bool
    {
        return $this->isOpen();
    }

    public function isOpen(): bool
    {
        if ($this->open === null) {
            $this->open = $this->start_time < now() && $this->end_time > now();
        }
        return $this->open;
    }

    public function getStatusLabel(): string
    {
        if ($this->isOpen()) {
            return __('Open');
        }
        if (now()->isBefore($this->start_time)) {
            return __('Upcoming');
        }
        if (auth()->user()->can('edit downtimes') && $this->processed) {
            return __('Processed');
        }
        return __('Closed');
    }

    public function getResearchProjectsAttribute(): Collection
    {
        return once(function () {
            $projects = ResearchProject::whereHas('downtimeActions', function ($query) {
                $query->where('downtime_id', $this->id)
                    ->where('action_type_id', ActionType::ACTION_RESEARCHING);
            })->get();
            if ($projects->isEmpty()) {
                $projects = ResearchProject::where('status', ResearchProject::STATUS_ACTIVE)
                    ->where('visibility', ResearchProject::VISIBILITY_PUBLIC)
                    ->get();
            }
            return $projects;
        });
    }

    public function getResearchProjectsForCharacter($characterId): Collection
    {
        $cacheKey = "research_projects_{$this->id}_{$characterId}";
        return cache()->remember($cacheKey, 1, function () use ($characterId) {
            $skillMatches = ResearchProject::where('status', ResearchProject::STATUS_ACTIVE)
                ->join('research_project_skill', 'research_project_skill.research_project_id', 'research_projects.id')
                ->join('character_skills', 'character_skills.skill_id', 'research_project_skill.skill_id')
                ->where('character_skills.character_id', $characterId)
                ->where('character_skills.completed', true)
                ->select('research_projects.*');
            $specialtyMatches = ResearchProject::where('status', ResearchProject::STATUS_ACTIVE)
                ->join('research_project_skill_specialty', 'research_project_skill_specialty.research_project_id', 'research_projects.id')
                ->join('character_skill_skill_specialty', 'character_skill_skill_specialty.skill_specialty_id', 'research_project_skill_specialty.skill_specialty_id')
                ->join('character_skills', 'character_skills.id', 'character_skill_skill_specialty.character_skill_id')
                ->where('character_skills.character_id', $characterId)
                ->where('character_skills.completed', true)
                ->select('research_projects.*');
            if ($skillMatches->count() > 0) {
                $skillIds = [];
                foreach ($skillMatches->get() as $project) {
                    foreach ($project->skillSpecialties as $specialty) {
                        $skillIds = array_merge($specialty->specialtyType->skills->pluck('id')->toArray(), $skillIds);
                    }
                }
                if (count($skillIds) > 0) {
                    $skillMatches = $skillMatches->whereNotIn('research_project_skill.skill_id', array_unique($skillIds));
                }
            }
            return $skillMatches->union($specialtyMatches)->get();
        });
    }

    public function getResearchVolunteerProjectsAttribute(): Collection
    {
        return once(fn() => ResearchProject::where('needs_volunteers', true)
            ->where('status', ResearchProject::STATUS_ACTIVE)
            ->get());
    }

    public function trainingCourses(): HasMany
    {
        return $this->actions()->where('action_type_id', ActionType::ACTION_TEACHING)
            ->join('character_skills', 'character_skill_id', 'character_skills.id')
            ->join('skills', 'skill_id', 'skills.id')
            ->orderBy('skills.name');
    }

    public function getTrainees($skillId): Collection
    {
        $cacheKey = "trainees_{$this->id}_{$skillId}";
        return cache()->remember($cacheKey, 300, function () use ($skillId) {
            return $this->actions()->where('action_type_id', ActionType::ACTION_TRAINING)
                ->join('character_skills', 'character_skill_id', 'character_skills.id')
                ->where('skill_id', $skillId)
                ->selectRaw('DISTINCT downtime_actions.character_id')
                ->get();
        });
    }

    public function countTrainees($skillId): int
    {
        $count = count($this->getTrainees($skillId));
        $skill = Skill::find($skillId);
        foreach ($skill->subSkills as $subSkill) {
            $count += count($this->getTrainees($subSkill->id));
        }
        return $count;
    }

    public function getCharacters(): Collection
    {
        return once(function () {
            return $this->actions()->with('character')->get()->pluck('character')->unique('id');
        });
    }

    public function getEligibleUsers(): Collection
    {
        return once(function () {
            if ($this->event_id) {
                $event = Event::find($this->event_id);
                $users = $event->users;
            } else {
                $users = User::all();
            }
            return $users;
        });
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
                case ActionType::ACTION_TEACHING:
                    $taughtSkills[$action->characterSkill->skill_id][$action->character_id] = $action;
                    break;
                case ActionType::ACTION_TRAINING:
                    $trainedSkills[$action->characterSkill->skill_id][$action->character_id][] = $action;
                    break;
                case ActionType::ACTION_MISSION:
                    $downtimeMissions[$action->downtime_mission_id][] = $action->character_id;
                    break;
                case ActionType::ACTION_RESEARCHING:
                case ActionType::ACTION_RESEARCH_SUBJECT:
                    $researchProjects[$action->research_project_id][] = $action;
                    break;
                case ActionType::ACTION_UPKEEP:
                case ActionType::ACTION_UPKEEP_2:
                    $upkeepMaintenance[$action->characterSkill->skill_id][$action->character_id] = $action;
                    break;
            }
        }
        $upkeepSkills = Skill::where('upkeep', true);
        $requiredUpkeepSkills = [];
        foreach ($upkeepSkills as $skill) {
            $relevantCharacters = [];
            $relevantCharacterSkills = $skill->characterSkills()->whereIn('character_id', $characters)->get();
            foreach ($relevantCharacterSkills as $characterSkill) {
                $relevantCharacters[$characterSkill->character_id][] = $characterSkill->id;
            }
            $requiredCharacters = [];
            foreach ($relevantCharacters as $characterId => $skills) {
                $requiredCharacters[$characterId] = $characterId;
            }
            $requiredUpkeepSkills[$skill->id] = $requiredCharacters;
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

    public function process()
    {
        extract($this->preprocess());
        $this->actions->keyBy('id');
        $skillChanges = [];
        $skills = [];
        foreach ($trainedSkills as $skillId => $characters) {
            $skill = Skill::find($skillId);
            $skills[$skillId] = $skill;
            foreach ($characters as $characterId => $actions) {
                $skillChanges[$skillId][] = [
                    'character_id' => $characterId,
                    'character_skill_id' => current($actions)->character_skill_id,
                    'log_type_id' => LogType::DOWNTIME,
                    'amount_trained' => count($actions),
                    'locked' => true,
                    'downtime_id' => $this->id,
                    'notes' => __('Trained :skill', ['skill' => $skill->name]),
                ];
            }
        }
        foreach ($taughtSkills as $skillId => $characters) {
            if (empty($skills[$skillId])) {
                $skill = Skill::find($skillId);
                $skills[$skillId] = $skill;
            }
            $teachers = [];
            foreach ($characters as $characterId => $action) {
                $teachers[$characterId] = $characterId;
                $skillChanges[$skillId][] = [
                    'character_id' => $characterId,
                    'character_skill_id' => $action->character_skill_id,
                    'log_type_id' => LogType::DOWNTIME,
                    'amount_trained' => 0,
                    'teacher_id' => $characterId,
                    'locked' => true,
                    'downtime_id' => $this->id,
                    'notes' => __('Taught :skill', ['skill' => $skills[$skillId]->name]),
                    'temp_vigor_change' => 1,
                ];
            }
            if (!empty($skillChanges[$skillId])) {
                $trainedCharacters = [];
                foreach ($skillChanges[$skillId] as &$skillChange) {
                    if ($skillChange['amount_trained'] > 0 && (!in_array($skillChange['character_id'], $teachers) || count($teachers) > 1)) {
                        $skillChange['teacher_id'] = $characterId;
                        $trainedCharacters[] = $skillChange['character_id'];
                        $skillChange['amount_trained']++;
                    }
                }
                foreach ($skills[$skillId]->subSkills as $subSkill) {
                    if (!empty($skillChanges[$subSkill->id])) {
                        foreach ($skillChanges[$subSkill->id] as &$skillChange) {
                            if ($skillChange['amount_trained'] > 0 && $skillChange['character_id'] != $characterId && !in_array($skillChange['character_id'], $trainedCharacters)) {
                                $skillChange['teacher_id'] = $characterId;
                                $skillChange['amount_trained']++;
                            }
                        }
                    }
                }
            }
        }
        foreach ($requiredUpkeepSkills as $skillId => $characters) {
            if (empty($skills[$skillId])) {
                $skill = Skill::find($skillId);
                $skills[$skillId] = $skill;
            }
            foreach ($characters as $characterId) {
                if (!isset($upkeepMaintenance[$skillId][$characterId])) {
                    $skillChanges[$skillId][] = [
                        'character_id' => $characterId,
                        'log_type_id' => LogType::DOWNTIME,
                        'amount_trained' => 0,
                        'locked' => true,
                        'downtime_id' => $this->id,
                        'notes' => __('Removed :skill due to lack of upkeep', ['skill' => $skills[$skillId]->name]),
                    ];
                    $characterSkill = CharacterSkill::where('character_id', $characterId)
                        ->where('skill_id', $skillId)
                        ->first();
                    $characterSkill->removed = true;
                    $characterSkill->save();
                } else {
                    $action = $upkeepMaintenance[$skillId][$characterId];
                    $skillChanges[$skillId][] = [
                        'character_id' => $characterId,
                        'character_skill_id' => $action->character_skill_id,
                        'log_type_id' => LogType::DOWNTIME,
                        'amount_trained' => 0,
                        'locked' => true,
                        'downtime_id' => $this->id,
                        'notes' => __('Maintenance of :skill', ['skill' => $skills[$skillId]->name]),
                    ];
                }
            }
        }
        $allResults = [];
        foreach ($skillChanges as $skillId => $changes) {
            foreach ($changes as $change) {
                $log = new CharacterLog();
                $log->fill($change);
                $log->save();
                $result = [
                    'skill' => $skills[$skillId]->name,
                    'notes' => $log->notes,
                ];
                if (!empty($change['amount_trained'])) {
                    $result['amount_trained'] = $change['amount_trained'];

                    $characterSkill = CharacterSkill::find($log->character_skill_id);
                    if ($characterSkill->completed) {
                        $result['skill_completed'] = true;
                    }
                }
                if (!empty($change['body_change'])) {
                    $result['body_change'] = $change['body_change'];
                }
                if (!empty($change['temp_body_change'])) {
                    $result['temp_body_change'] = $change['temp_body_change'];
                }
                if (!empty($change['vigor_change'])) {
                    $result['vigor_change'] = $change['vigor_change'];
                }
                if (!empty($change['temp_vigor_change'])) {
                    $result['temp_vigor_change'] = $change['temp_vigor_change'];
                }

                $allResults[$log['character_id']][] = $result;
            }
        }

        foreach ($this->personalActions() as $action) {
            if (!empty($action->notes) || !empty($action->response)) {
                $allResults[$action->character_id][] = [
                    'notes' => $action->notes,
                    'response' => $action->response,
                ];
            }
        }

        $researchResults = [];
        foreach ($this->researchProjects as $project) {
            $researchResults[$project->id] = [
                'project' => $project,
                'contributors' => [],
                'volunteers' => [],
                'results' => ResearchProject::STATUS_COMPLETED == $project->status ? $project->results : null,
            ];
        }
        foreach ($this->researchActions() as $action) {
            if (!isset($researchResults[$action->research_project_id])) {
                continue;
            }
            $researchResults[$action->research_project_id]['contributors'][$action->character_id][] = $action->character->listName;
        }
        foreach ($this->researchSubjectActions() as $action) {
            if (!isset($researchResults[$action->research_project_id])) {
                continue;
            }
            $researchResults[$action->research_project_id]['volunteers'][] = $action->character->listName;
        }

        foreach ($allResults as $characterId => $results) {
            $character = Character::find($characterId);
            if (in_array($character->status_id, [Status::APPROVED, Status::INACTIVE])) {
                $character->status_id = Status::PLAYED;
                $character->save();
            }
            Mail::to($character->user->email, $character->user->name)->send(new DowntimeProcessed($this, $character, $results, $researchResults));
            if ('local' == env('APP_ENV')) {
                break;
            }
        }
        $this->processed = true;
        $this->save();
    }

    public function personalActions(): Collection
    {
        return once(fn() => $this->actions()->where('action_type_id', ActionType::ACTION_OTHER)->get());
    }

    public function researchActions(): Collection
    {
        return once(fn() => $this->actions()->with(['researchProject', 'character'])
            ->where('action_type_id', ActionType::ACTION_RESEARCHING)->get());
    }

    public function researchSubjectActions(): Collection
    {
        return once(fn() => $this->actions()->with(['researchProject', 'character'])
            ->where('action_type_id', ActionType::ACTION_RESEARCH_SUBJECT)->get());
    }
}
