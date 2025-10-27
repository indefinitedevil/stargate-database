<?php

namespace App\Models;

use App\Listeners\RollTraits;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @property int id
 * @property string name
 * @property string short_name
 * @property string pronouns
 * @property int user_id
 * @property int background_id
 * @property int status_id
 * @property Background background
 * @property CharacterSkill[]|Collection skills
 * @property CharacterSkill[]|Collection trainedSkills
 * @property CharacterSkill[]|Collection trainedSkillsWithoutSystem
 * @property CharacterSkill[]|Collection displayedSkills
 * @property CharacterSkill[]|Collection displayedTrainedSkills
 * @property CharacterSkill[]|Collection hiddenTrainedSkills
 * @property CharacterSkill[]|Collection trainingSkills
 * @property CharacterSkill[]|Collection upkeepSkills
 * @property CharacterSkill[]|Collection requiredUpkeepSkills
 * @property CharacterSkill[]|Collection skillsWithSpecialties
 * @property CharacterSkill[]|Collection skillsWithMissingSpecialties
 * @property CharacterLog[]|Collection logs
 * @property Status status
 * @property Feat[] feats
 * @property User player
 * @property User user
 * @property int body
 * @property int vigor
 * @property int temp_body
 * @property int temp_vigor
 * @property string rank
 * @property string former_rank
 * @property string history
 * @property string character_links
 * @property string plot_notes
 * @property string other_abilities
 * @property Object[] cards
 * @property int completedTrainingMonths
 * @property int trainingMonths
 * @property bool isPrimary
 * @property int primary_secondary
 * @property Event[] events
 * @property int hero_scoundrel
 * @property string archetype
 * @property DowntimeAction[]|Collection downtimeActions
 * @property CharacterTrait[]|Collection characterTraits
 * @property string traits_indicator
 * @property string division
 * @property Division[]|Collection divisions
 * @property Department[]|Collection departments
 * @property Team[]|Collection teams
 */
class Character extends Model
{
    use HasFactory;

    const UNKNOWN = 0;
    const HERO = 1;
    const SCOUNDREL = 2;
    const VILLAIN = 3;

    protected $fillable = [
        'user_id',
        'name',
        'short_name',
        'background_id',
        'status_id',
        'history',
        'plot_notes',
        'other_abilities',
        'rank',
        'former_rank',
        'character_links',
        'primary_secondary',
        'hero_scoundrel',
        'pronouns',
    ];

    public function player(): BelongsTo
    {
        return $this->user();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function background(): BelongsTo
    {
        return $this->belongsTo(Background::class, 'id', 'background_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(CharacterLog::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(CharacterSkill::class)
            ->select('character_skills.*')
            ->join('skills', 'character_skills.skill_id', '=', 'skills.id')
            ->orderBy('skills.name');
    }

    public function getAvailableSkillsAttribute(): Collection
    {
        $skillsWithAllPrerequisitesUnmet = SkillPrereq::select('skill_prereqs.skill_id')
            ->leftJoin('character_skills', function (JoinClause $join) {
                $join->on('skill_prereqs.prereq_id', '=', 'character_skills.skill_id');
                $join->on('character_skills.character_id', '=', DB::raw($this->id));
                $join->where('character_skills.completed', true);
                $join->where('skill_prereqs.always_required', true);
            })
            ->whereNull('character_skills.id');
        $skillsWithAnyPrerequisitesMet = SkillPrereq::select('skill_prereqs.skill_id')
            ->leftJoin('character_skills', function (JoinClause $join) {
                $join->on('skill_prereqs.prereq_id', '=', 'character_skills.skill_id');
                $join->on('character_skills.character_id', '=', DB::raw($this->id));
                $join->where('character_skills.completed', true);
                $join->where('skill_prereqs.always_required', false);
            })
            ->whereNotNull('character_skills.id');
        $lockedOutSkills = SkillLockout::select('skill_lockouts.lockout_id')
            ->join('character_skills', function (JoinClause $join) {
                $join->on('skill_lockouts.skill_id', '=', 'character_skills.skill_id');
                $join->on('character_skills.character_id', '=', DB::raw($this->id));
            });
        $skills = Skill::select('skills.*')
            ->leftJoin('character_skills', function (JoinClause $join) {
                $join->on('skills.id', '=', 'character_skills.skill_id');
                $join->on('character_skills.character_id', '=', DB::raw($this->id));
            })
            ->whereNotIn('skills.id', $skillsWithAllPrerequisitesUnmet)
            ->whereNotIn('skills.id', $lockedOutSkills)
            ->whereNotIn('skills.id', $this->background->skills()->select('skills.id'))
            ->where(function (Builder $query) {
                $query->whereNull('character_skills.id')
                    ->orWhere('skills.repeatable', '>', 0);
            });
        $user = Auth::user();
        if ($user->cannot('edit all characters')) {
            $skills->where('skills.skill_category_id', '!=', 7);
        }

        $skillsWithAnyPrerequisiteMet = Skill::select('skills.*')
            ->leftJoin('character_skills', function (JoinClause $join) {
                $join->on('skills.id', '=', 'character_skills.skill_id');
                $join->on('character_skills.character_id', '=', DB::raw($this->id));
            })
            ->whereIn('skills.id', $skillsWithAnyPrerequisitesMet)
            ->whereNotIn('skills.id', $lockedOutSkills)
            ->whereNotIn('skills.id', $this->background->skills()->select('skills.id'))
            ->where(function (Builder $query) {
                $query->whereNull('character_skills.id')
                    ->orWhere('skills.repeatable', '>', 0);
            });
        if ($user->cannot('edit all characters')) {
            $skills->where('skills.skill_category_id', '!=', 7);
        }

        if ($this->status_id < Status::APPROVED) {
            // Check pre-requisites and lockouts for background skills
            $skillsWithAllPrerequisitesUnmet = SkillPrereq::select('skill_prereqs.skill_id')
                ->join('background_skill', function (JoinClause $join) {
                    $join->on('skill_prereqs.prereq_id', '=', 'background_skill.skill_id');
                    $join->on('background_skill.background_id', '=', DB::raw($this->background_id));
                });
            $lockedOutSkills = SkillLockout::select('skill_lockouts.lockout_id')
                ->join('background_skill', function (JoinClause $join) {
                    $join->on('skill_lockouts.skill_id', '=', 'background_skill.skill_id');
                    $join->on('background_skill.background_id', '=', DB::raw($this->background_id));
                });
            $backgroundSkills = Skill::select('skills.*')
                ->whereNotIn('skills.id', $this->background->skills()->select('skills.id'))
                ->whereIn('skills.id', $skillsWithAllPrerequisitesUnmet)
                ->whereNotIn('skills.id', $lockedOutSkills);
            if ($user->cannot('edit all characters')) {
                $skills->where('skills.skill_category_id', '!=', 7);
            }

            return $skills->union($backgroundSkills)
                ->union($skillsWithAnyPrerequisiteMet)
                ->orderBy('skill_category_id')
                ->orderBy('name')
                ->get();
        }
        return $skills->union($skillsWithAnyPrerequisiteMet)
            ->orderBy('skill_category_id')
            ->orderBy('name')
            ->get();
    }

    public function trainedSkills(): HasMany
    {
        return $this->skills()->where('completed', true)
            ->where('removed', false);
    }

    public function upkeepSkills(): HasMany
    {
        return $this->skills()->where('completed', true)
            ->where('upkeep', true);
    }

    public function getRequiredUpkeepSkillsAttribute(): Collection
    {
        return $this->upkeepSkills;
    }

    public function displayedTrainedSkills(): HasMany
    {
        return $this->trainedSkills()
            ->where('skills.display', true);
    }

    public function trainedSkillsWithoutSystem(): HasMany
    {
        return $this->trainedSkills()
            ->where('skill_category_id', '!=', SkillCategory::SYSTEM);
    }

    public function teachingSkills(): HasMany
    {
        return $this->trainedSkillsWithoutSystem()
            ->where('skills.id', '!=', Skill::LEADERSHIP_EXTRA_PERSON);
    }

    public function hiddenTrainedSkills(): HasMany
    {
        return $this->trainedSkills()->where('skills.display', false);
    }

    public function trainingSkills(): HasMany
    {
        return $this->skills()->where('completed', false)
            ->where('skill_category_id', '!=', SkillCategory::SYSTEM);
    }

    public function skillsWithSpecialties(): HasMany
    {
        return $this->skills()
            ->where('skills.specialties', '>', 0);
    }

    public function getSkillsWithMissingSpecialtiesAttribute(): Collection
    {
        $missingSpecialties = [];
        foreach ($this->skillsWithSpecialties as $characterSkill) {
            if ($characterSkill->specialties->count() < $characterSkill->skill->specialties) {
                $missingSpecialties[] = $characterSkill;
            }
        }
        return collect($missingSpecialties);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function getFeatsAttribute()
    {
        $feats = $this->background->feats->all();
        foreach ($this->background->skills as $skill) {
            if (!empty($skill->feats)) {
                $feats = array_merge($feats, $skill->feats->all());
            }
        }
        foreach ($this->trainedSkills as $characterSkill) {
            if (!empty($characterSkill->skill->feats)) {
                $feats = array_merge($feats, $characterSkill->skill->feats->all());
            }
        }
        usort($feats, array($this, 'nameCompare'));
        return $this->uniqueArray($feats);
    }

    public function getBodyAttribute(): int
    {
        $body = $this->background->body;
        foreach ($this->trainedSkills as $characterSkill) {
            $body += $characterSkill->skill->body;
        }
        foreach ($this->logs->where('body_change', '!=', 0) as $log) {
            $body += $log->body_change;
        }
        return $body;
    }

    public function getTempBodyAttribute(): int
    {
        $body = 0;
        foreach ($this->logs->where('temp_body_change', '!=', 0) as $log) {
            $body += $log->temp_body_change;
        }
        return $body;
    }

    public function getVigorAttribute(): int
    {
        $vigor = $this->background->vigor;
        foreach ($this->trainedSkills as $characterSkill) {
            $vigor += $characterSkill->skill->vigor;
        }
        foreach ($this->logs->where('vigor_change', '!=', 0) as $log) {
            $vigor += $log->vigor_change;
        }
        return $vigor;
    }

    public function getTempVigorAttribute(): int
    {
        $vigor = 0;
        foreach ($this->logs->where('temp_vigor_change', '!=', 0) as $log) {
            $vigor += $log->temp_vigor_change;
        }
        return $vigor;
    }

    public function getCompletedTrainingMonthsAttribute(): int
    {
        $totalTraining = 0;
        foreach ($this->trainedSkills as $characterSkill) {
            $totalTraining += $characterSkill->trained;
        }
        return $totalTraining;
    }

    public function getTrainingMonthsAttribute(): int
    {
        $totalTraining = 0;
        foreach ($this->skills as $characterSkill) {
            $totalTraining += $characterSkill->trained;
        }
        return $totalTraining;
    }

    public function getCardsAttribute(): array
    {
        $unsortedCards = [];
        foreach ($this->trainedSkills as $characterSkill) {
            $skillCards = $characterSkill->skill->cards;
            if (!empty($skillCards)) {
                foreach ($skillCards as $skillCard) {
                    if (!isset($unsortedCards[$skillCard->id][$skillCard->pivot->total])) {
                        $card = new \stdClass;
                        $card->id = $skillCard->id;
                        $card->name = $skillCard->name;
                        $card->number = $skillCard->pivot->number;
                        $unsortedCards[$skillCard->id][$skillCard->pivot->total] = $card;
                    } elseif ($skillCard->pivot->total) {
                        $unsortedCards[$skillCard->id][$skillCard->pivot->total]->number += $skillCard->pivot->number;
                    }
                }
            }
        }
        $cards = [];
        foreach ($unsortedCards as $unsortedCard) {
            if (!empty($unsortedCard[1])) {
                $cards[] = $unsortedCard[1];
            } else {
                $cards[] = $unsortedCard[0];
            }
        }
        usort($cards, array($this, 'nameCompare'));
        return $cards;
    }

    private function nameCompare($a, $b): int
    {
        return strcmp($a->name, $b->name);
    }

    private function uniqueArray($array): array
    {
        $ids = [];
        $uniqueArray = [];
        foreach ($array as $item) {
            if (!in_array($item->id, $ids)) {
                $ids[] = $item->id;
                $uniqueArray[] = $item;
            }
        }
        return $uniqueArray;
    }

    public function events(): BelongsToMany
    {
        return $this->user->belongsToMany(Event::class)
            ->wherePivot('character_id', $this->id)
            ->withPivot('attended', 'role');
    }

    public function getArchetypeAttribute(): string
    {
        return self::getArchetypeName($this->hero_scoundrel);
    }

    public static function getArchetypeName(int $archetype): string
    {
        if ($archetype === self::HERO) {
            return __('Hero');
        } elseif ($archetype === self::SCOUNDREL) {
            return __('Scoundrel');
        } elseif ($archetype === self::VILLAIN) {
            return __('Villain');
        } else {
            return __('Unknown');
        }
    }

    public function getIsPrimaryAttribute(): bool
    {
        return $this->primary_secondary;
    }

    public function downtimeActions(): HasMany
    {
        return $this->hasMany(DowntimeAction::class);
    }

    public function canBeReset(): bool
    {
        return in_array($this->status_id, [Status::APPROVED, Status::PLAYED]) && $this->downtimeActions->isEmpty();
    }

    public function reset()
    {
        $this->status_id = Status::NEW;
        $this->save();

        $logs = CharacterLog::where('character_id', $this->id)
            ->where('log_type_id', LogType::CHARACTER_CREATION)
            ->get();
        foreach ($logs as $log) {
            $log->delete();
        }

        foreach ($this->background->skills as $skill) {
            $characterSkill = CharacterSkill::where('character_id', $this->id)
                ->where('skill_id', $skill->id)
                ->first();
            if ($characterSkill) {
                $characterSkill->delete();
            }
        }
    }

    public function getViewRoute(): string
    {
        $name = $this->short_name ?: $this->name;
        return route('characters.view', ['characterId' => $this, 'characterName' => Str::slug($name)]);
    }

    public function getLogsRoute(): string
    {
        $name = $this->short_name ?: $this->name;
        return route('characters.logs', ['characterId' => $this, 'characterName' => Str::slug($name)]);
    }

    public function getListNameAttribute(): string
    {
        return $this->short_name ?: $this->name;
    }

    public function characterTraits(): BelongsToMany
    {
        return $this->belongsToMany(CharacterTrait::class)
            ->withPivot('status');
    }

    public function getTraitsIndicatorAttribute(): string
    {
        if ($this->status_id < Status::APPROVED) {
            return __('N/A');
        }
        RollTraits::roll($this);
        $indicators = json_decode($this->attributes['traits_indicator']);
        if (empty($indicators) || count($indicators) < CharacterTrait::indicatorCount()) {
            $this->resetIndicators();
        }
        $return = '';
        foreach (json_decode($this->attributes['traits_indicator']) as $indicator) {
            $return .= '<i class="fa-solid ' . e($indicator) . '"></i> ';
        }
        return $return;
    }

    public function resetIndicators(): bool
    {
        $indicators = [];
        foreach ($this->characterTraits()->get() as $characterTrait) {
            if ($characterTrait->pivot->status) {
                $indicators[] = $characterTrait->icon;
            }
        }
        $keys = array_rand(CharacterTrait::TRAIT_MASKS, CharacterTrait::indicatorCount() - count($indicators));
        foreach ($keys as $key) {
            $indicators[] = CharacterTrait::TRAIT_MASKS[$key];
        }
        shuffle($indicators);
        $this->attributes['traits_indicator'] = json_encode($indicators);
        return $this->saveQuietly();
    }

    public function abilities(): array
    {
        return once(function () {
            $abilities = [];
            foreach ($this->trainedSkills as $characterSkill) {
                $skillAbilities = $characterSkill->skill->abilities();
                if ($characterSkill->skill->per_restore) {
                    if (Skill::LEADERSHIP == $characterSkill->skill_id) {
                        $leadershipCount = 1 + $this->skills()
                                ->where('skill_id', Skill::LEADERSHIP_EXTRA_PERSON)
                                ->where('completed', true)
                                ->where('removed', false)
                                ->count();
                        foreach ($skillAbilities as &$ability) {
                            $ability = trans_choice($ability, $leadershipCount, ['count' => $leadershipCount]);
                        }
                    }
                }
                $abilities = array_merge($abilities, $skillAbilities);
            }
            sort($abilities);
            return array_unique($abilities);
        });
    }

    public function divisions(): BelongsToMany
    {
        return $this->belongsToMany(Division::class)
            ->withPivot('position');
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class)
            ->withPivot('position');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)
            ->withPivot('position');
    }

    public function getDivisionAttribute(): string
    {
        $divisionNames = [];
        foreach ($this->divisions as $division) {
            $name = $division->name;
            if (Division::HEAD == $division->pivot->position) {
                if (1 == $division->id) {
                    $name .= ' (' . __('1IC') . ')';
                } else {
                    $name .= ' (' . __('Head') . ')';
                }
            } elseif (Division::SECOND == $division->pivot->position) {
                $name .= ' (' . __('2IC') . ')';
            } elseif (Division::STAFF == $division->pivot->position) {
                $name .= ' (' . __('Staff') . ')';
            }
            $divisionNames[] = $name;
        }
        if (empty($divisionNames)) {
            return $this->departments->first()?->division->name ?? '';
        }
        return implode(', ', $divisionNames);
    }

    public function getDivisionIdsAttribute(): array
    {
        return $this->divisions->pluck('id')->toArray();
    }

    public function getDepartmentAttribute(): string
    {
        $departmentNames = [];
        foreach ($this->departments as $department) {
            $name = $department->name;
            if (Department::HEAD == $department->pivot->position) {
                $name .= ' (' . __('Head') . ')';
            } elseif (Department::SPECIALIST == $department->pivot->position) {
                $name .= ' (' . __('Specialist') . ')';
            }
            $departmentNames[] = $name;
        }
        return implode(', ', $departmentNames);
    }

    public function getDepartmentIdsAttribute(): array
    {
        return $this->departments->pluck('id')->toArray();
    }

    public function getTeamAttribute(): string
    {
        $teamNames = [];
        foreach ($this->teams()->whereNull('event_id')->get() as $team) {
            $teamNames[] = $this->getTeamName($team);
        }
        return implode(', ', $teamNames);
    }

    public function getTeamIdsAttribute(): array
    {
        return $this->teams->pluck('id')->toArray();
    }

    public function eventTeams(int $eventId): BelongsToMany
    {
        return $this->teams()->where('event_id', $eventId);
    }

    public function getEventTeam($eventId = null): string
    {
        $eventId ??= Event::nextEventId();
        if (empty($eventId)) {
            return '';
        }

        return $this->eventTeams($eventId)->get()
            ->map([$this, 'getTeamName'])
            ->implode(', ');
    }

    public function getTeamName($team): string
    {
        $name = $team->name;
        if (Team::LEAD == $team->pivot->position) {
            $name .= ' (' . __('TL') . ')';
        } elseif (Team::SECOND == $team->pivot->position) {
            $name .= ' (' . __('2IC') . ')';
        }
        return $name;
    }

    public static function getActiveCharacters(): Collection
    {
        return once(function () {
            return self::whereIn('status_id', [Status::APPROVED, Status::PLAYED])
                ->orderBy('name')
                ->get();
        });
    }
}
