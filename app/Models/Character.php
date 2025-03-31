<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @property int id
 * @property string name
 * @property string short_name
 * @property int user_id
 * @property int background_id
 * @property int status_id
 * @property Background background
 * @property Collection skills
 * @property Collection trainedSkills
 * @property Collection displayedSkills
 * @property Collection displayedTrainedSkills
 * @property Collection hiddenTrainedSkills
 * @property Collection trainingSkill
 * @property Collection logs
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
 * @property Object[] cards
 * @property int completedTrainingMonths
 * @property bool isPrimary
 * @property int primary_secondary
 * @property Event[] events
 * @property int hero_scoundrel
 * @property string type
 * @property Collection downtimeActions
 */
class Character extends Model
{
    use HasFactory;

    const HERO = 1;
    const SCOUNDREL = 2;

    protected $fillable = [
        'user_id',
        'name',
        'short_name',
        'background_id',
        'status_id',
        'history',
        'plot_notes',
        'rank',
        'former_rank',
        'character_links',
        'primary_secondary',
        'hero_scoundrel',
    ];

    public function player(): BelongsTo
    {
        return $this->user();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function background(): HasOne
    {
        return $this->hasOne(Background::class, 'id', 'background_id');
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

        // Check pre-requisites and
        // lockouts for background skills
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

        return $skills->union($backgroundSkills)
            ->union($skillsWithAnyPrerequisiteMet)
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

    public function displayedTrainedSkills(): HasMany
    {
        return $this->trainedSkills()
            ->where('skills.display', true);
    }

    public function hiddenTrainedSkills(): HasMany
    {
        return $this->trainedSkills()->where('skills.display', false);
    }

    public function trainingSkills(): HasMany
    {
        return $this->skills()->where('completed', false);
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
        $body = $this->background->body;
        foreach ($this->trainedSkills as $characterSkill) {
            $body += $characterSkill->skill->body;
        }
        foreach ($this->logs->where('temp_body_change', '!=', 0) as $log) {
            $body += $log->body_change;
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
            $vigor += $log->vigor_change;
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

    public function getCardsAttribute(): array
    {
        $unsortedCards = [];
        foreach ($this->trainedSkills as $characterSkill) {
            $skillCards = $characterSkill->skill->cards;
            if (!empty($skillCards)) {
                foreach ($skillCards as $skillCard) {
                    if (!isset($unsortedCards[$skillCard->id][$skillCard->pivot->total])) {
                        $card = new \stdClass;
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

    public function getTypeAttribute(): string
    {
        if ($this->hero_scoundrel === self::HERO) {
            return __('Hero');
        } elseif ($this->hero_scoundrel === self::SCOUNDREL) {
            return __('Scoundrel');
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

        $logs = CharacterLog::where('character_id', $this->id)->get();
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
        return route('characters.view-pretty', ['characterId' => $this, 'characterName' => Str::slug($name)]);
    }

    public function getListNameAttribute(): string
    {
        return $this->short_name ?: $this->name;
    }
}
