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
 * @property bool $open
 * @property bool $processed
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

    public function getResearchProjects(): Collection
    {
        return ResearchProject::all();
    }

    public function trainingCourses(): HasMany
    {
        return $this->actions()->where('action_type_id', ActionType::TEACHING)
            ->join('character_skills', 'character_skill_id', 'character_skills.id')
            ->join('skills', 'skill_id', 'skills.id')
            ->orderBy('skills.name');
    }

    public function getTrainees($skillId): Collection
    {
        static $trainees = [];
        if (!isset($trainees[$skillId])) {
            $trainees[$skillId] = $this->actions()->where('action_type_id', ActionType::TRAINING)
                ->join('character_skills', 'character_skill_id', 'character_skills.id')
                ->where('skill_id', $skillId)
                ->selectRaw('DISTINCT downtime_actions.character_id')
                ->get();
        }
        return $trainees[$skillId];
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
                    $taughtSkills[$action->characterSkill->skill_id][$action->id] = $action->character_id;
                    break;
                case ActionType::TRAINING:
                    $trainedSkills[$action->characterSkill->skill_id][$action->character_id][] = $action->id;
                    break;
                case ActionType::MISSION:
                    $downtimeMissions[$action->downtime_mission_id][] = $action->character_id;
                    break;
                case ActionType::RESEARCH:
                    $researchProjects[$action->research_project_id][] = $action->character_id;
                    break;
                case ActionType::UPKEEP:
                case ActionType::UPKEEP_2:
                    $upkeepMaintenance[$action->characterSkill->skill_id][$action->id] = $action->character_id;
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

    public function process()
    {
        extract($this->preprocess());
        $skillChanges = [];
        $skills = [];
        foreach ($trainedSkills as $skillId => $characters) {
            $skill = Skill::find($skillId);
            $skills[$skillId] = $skill;
            foreach ($characters as $characterId => $actions) {
                $action = DowntimeAction::find(current($actions));
                $skillChanges[$skillId][] = [
                    'character_id' => $characterId,
                    'character_skill_id' => $action->character_skill_id,
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
            foreach ($characters as $actionId => $characterId) {
                $action = DowntimeAction::find($actionId);
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
                foreach ($skillChanges[$skillId] as &$skillChange) {
                    if ($skillChange['amount_trained'] > 0 && $skillChange['character_id'] != $characterId) {
                        $skillChange['teacher_id'] = $characterId;
                        $skillChange['amount_trained']++;
                    }
                }
                foreach ($skills[$skillId]->subSkills as $subSkill) {
                    if (!empty($skillChanges[$subSkill->id])) {
                        foreach ($skillChanges[$subSkill->id] as &$skillChange) {
                            if ($skillChange['amount_trained'] > 0 && $skillChange['character_id'] != $characterId) {
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
                    $actionId = current($upkeepMaintenance[$skillId]);
                    $action = DowntimeAction::find($actionId);
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

        foreach ($allResults as $characterId => $results) {
            $character = Character::find($characterId);
            if (in_array($character->status_id, [Status::APPROVED, Status::INACTIVE])) {
                $character->status_id = Status::PLAYED;
                $character->save();
            }
            Mail::to($character->user->email, $character->user->name)->send(new DowntimeProcessed($this, $character, $results));
            if ('local' == env('APP_ENV')) {
                break;
            }
        }
        $this->processed = true;
        $this->save();
    }

    public function miscActions(): Collection
    {
        static $miscActions = null;
        if (is_null($miscActions)) {
            $miscActions = $this->actions()->where('action_type_id', ActionType::OTHER)->get();
        }
        return $miscActions;
    }
}
