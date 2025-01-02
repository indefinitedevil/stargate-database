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

/**
 * @property int id
 * @property string name
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
 * @property Status status
 * @property Feat[] feats
 * @property User player
 * @property User user
 * @property int body
 * @property int vigor
 * @property string rank
 * @property string former_rank
 * @property string history
 * @property string plot_notes
 * @property Object[] cards
 * @property int completedTrainingMonths
 */
class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'background_id',
        'status_id',
        'history',
        'plot_notes',
        'rank',
        'former_rank',
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

    public function skills(): HasMany
    {
        return $this->hasMany(CharacterSkill::class)
            ->select('character_skills.*')
            ->join('skills', 'character_skills.skill_id', '=', 'skills.id')
            ->orderBy('skills.name');
    }

    public function getAvailableSkillsAttribute(): Collection
    {
        $prereqSkills = SkillPrereq::select('skill_prereqs.skill_id')
            ->leftJoin('character_skills', function (JoinClause $join) {
                $join->on('skill_prereqs.prereq_id', '=', 'character_skills.skill_id');
                $join->on('character_skills.character_id', '=', DB::raw($this->id));
            })
            ->whereNull('character_skills.id');
        $lockoutSkills = SkillLockout::select('skill_lockouts.lockout_id')
            ->join('character_skills', function (JoinClause $join) {
                $join->on('skill_lockouts.skill_id', '=', 'character_skills.skill_id');
                $join->on('character_skills.character_id', '=', DB::raw($this->id));
            });
        $skills = Skill::select('skills.*')
            ->leftJoin('character_skills', function (JoinClause $join) {
                $join->on('skills.id', '=', 'character_skills.skill_id');
                $join->on('character_skills.character_id', '=', DB::raw($this->id));
            })
            ->whereNotIn('skills.id', $prereqSkills)
            ->whereNotIn('skills.id', $lockoutSkills)
            ->whereNotIn('skills.id', $this->background->skills()->select('skills.id'))
            ->where(function (Builder $query) {
                $query->whereNull('character_skills.id')
                    ->orWhere('skills.repeatable', '>', 0);
            });

        $prereqSkills = SkillPrereq::select('skill_prereqs.skill_id')
            ->join('background_skill', function (JoinClause $join) {
                $join->on('skill_prereqs.prereq_id', '=', 'background_skill.skill_id');
                $join->on('background_skill.background_id', '=', DB::raw($this->background_id));
            });
        $lockoutSkills = SkillLockout::select('skill_lockouts.lockout_id')
            ->join('background_skill', function (JoinClause $join) {
                $join->on('skill_lockouts.skill_id', '=', 'background_skill.skill_id');
                $join->on('background_skill.background_id', '=', DB::raw($this->background_id));
            });
        $backgroundSkills = Skill::select('skills.*')
            ->whereNotIn('skills.id', $this->background->skills()->select('skills.id'))
            ->whereIn('skills.id', $prereqSkills)
            ->whereNotIn('skills.id', $lockoutSkills);

        return $skills->union($backgroundSkills)
            ->orderBy('name')->get();
    }

    public function trainedSkills(): HasMany
    {
        return $this->skills()->where('completed', true);
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
        return $body;
    }

    public function getVigorAttribute(): int
    {
        $vigor = $this->background->vigor;
        foreach ($this->trainedSkills as $characterSkill) {
            $vigor += $characterSkill->skill->vigor;
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
        return $this->belongsToMany(Event::class)
            ->withPivot('attended', 'role')
            ->withTimestamps();
    }
}
