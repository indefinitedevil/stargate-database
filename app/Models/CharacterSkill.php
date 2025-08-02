<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int id
 * @property Character character
 * @property int character_id
 * @property Skill skill
 * @property int skill_id
 * @property bool discount_used
 * @property bool discount_available
 * @property CharacterSkill discountUsedBy
 * @property Collection discountedBy
 * @property Collection skillSpecialties
 * @property Collection specialties
 * @property Collection allSpecialties
 * @property Collection characterLogs
 * @property bool completed
 * @property bool locked
 * @property int cost
 * @property int remainingCost
 * @property int trained
 * @property int level
 * @property string name
 * @property string printName
 * @property bool removed
 */
class CharacterSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'character_id',
        'skill_id',
        'completed',
        'discount_used',
        'discount_used_by',
        'removed',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function discountUsedBy(): HasOne
    {
        return $this->hasOne(CharacterSkill::class, 'id', 'discount_used_by');
    }

    public function discountedBy(): HasMany
    {
        return $this->hasMany(CharacterSkill::class, 'discount_used_by', 'id');
    }

    public function skillSpecialties(): BelongsToMany
    {
        return $this->belongsToMany(SkillSpecialty::class)
            ->orderBy('skill_specialties.name');
    }

    public function getSpecialtiesAttribute(): Collection
    {
        return $this->skillSpecialties->sortBy('name');
    }

    public function getAllSpecialtiesAttribute(): Collection
    {
        $specialties = $this->skillSpecialties;
        if (Skill::ARCHEO_ANTHROPOLOGY == $this->skill_id && $this->completed) {
            $additionalSpecialties = $this->character->skills()
                ->where('skill_id', Skill::ADDITIONAL_AA_SPEC)
                ->where('completed', true)
                ->get();
            foreach ($additionalSpecialties as $additionalSpecialty) {
                $specialties = $specialties->concat($additionalSpecialty->skillSpecialties);
            }
        } elseif ($this->skill->specialties) {
            $additionalSpecialties = $this->character->skills()
                ->whereNot('character_skills.id', $this->id)
                ->where('skill_id', $this->skill_id)
                ->where('completed', true)
                ->get();
            foreach ($additionalSpecialties as $additionalSpecialty) {
                $specialties = $specialties->concat($additionalSpecialty->skillSpecialties);
            }
        }
        return $specialties->sortBy('name');
    }

    public function getCostAttribute(): int
    {
        if (!in_array($this->character->status_id, [Status::NEW, Status::READY]) && $this->completed) {
            return $this->trained;
        }
        $cost = $this->skill->cost($this->character);
        if ($this->discountedBy) {
            foreach ($this->discountedBy as $discountedBy) {
                $skillDiscount = SkillDiscount::where('discounted_skill', $this->skill_id)
                    ->where('discounting_skill', $discountedBy->skill_id)
                    ->first();
                $cost -= $skillDiscount->discount;
            }
        }
        return $cost;
    }

    public function getRemainingCostAttribute(): int
    {
        if ($this->completed) {
            return 0;
        }
        return $this->cost - $this->trained;
    }

    public function characterLogs(): HasMany
    {
        return $this->hasMany(CharacterLog::class);
    }

    public function getTrainedAttribute(): int
    {
        $trained = 0;
        if (in_array($this->character->status_id, [Status::NEW, Status::READY]) && $this->completed) {
            return $this->getCostAttribute();
        }
        foreach ($this->characterLogs as $characterLog) {
            $trained += $characterLog->amount_trained;
        }
        return $trained;
    }

    public function getNameAttribute(): string
    {
        return $this->formatName($this->skill->name);
    }

    public function getPrintNameAttribute(): string
    {
        return $this->formatName($this->skill->print_name ?? $this->skill->name);
    }

    protected function formatName($name): string
    {
        if (1 == $this->skill->specialties) {
            if ($this->skillSpecialties->count()) {
                return __(':name (:specialty)', ['name' => $name, 'specialty' => $this->skillSpecialties->first()->name]);
            }
            return __(':name (Not selected)', ['name' => $name]);
        }
        if ($this->skill->repeatable) {
            return __(':name (x:level)', ['name' => $name, 'level' => $this->level]);
        }
        if (Skill::LEADERSHIP == $this->skill_id) {
            $leadershipCount = 1 + $this->character->skills()
                    ->where('skill_id', Skill::LEADERSHIP_EXTRA_PERSON)
                    ->where('completed', true)
                    ->where('removed', false)
                    ->count();
            return trans_choice(':name (:count person)|:name (:count people)', $leadershipCount, ['name' => $name, 'count' => $leadershipCount]);
        }
        return $name;
    }

    public function getLockedAttribute(): bool
    {
        return $this->characterLogs->where('locked', true)->count() > 0;
    }

    public function getRequiredAttribute(): bool
    {
        return $this->character->skills()
                ->join('skill_prereqs', 'skills.id', '=', 'skill_prereqs.skill_id')
                ->where('skill_prereqs.prereq_id', $this->skill_id)
                ->count() > 0;
    }

    public function getRequiredByAttribute()
    {
        $skills = $this->character->skills()
            ->join('skill_prereqs', 'skills.id', '=', 'skill_prereqs.skill_id')
            ->where('skill_prereqs.prereq_id', $this->skill_id)
            ->get('skills.name');
        return $skills->implode('name', ', ');
    }

    public function getDiscountAvailableAttribute(): bool
    {
        return $this->skill->hasMany(SkillDiscount::class, 'discounted_skill')
                ->join('character_skills', 'discounting_skill', '=', 'character_skills.skill_id')
                ->where('character_skills.character_id', $this->character_id)
                ->where('character_skills.completed', true)
                ->where('character_skills.discount_used', false)
                ->count() > 0;
    }

    public function getDiscountsAvailableAttribute(): Collection
    {
        return $this->skill->hasMany(SkillDiscount::class, 'discounted_skill')
            ->join('skills', 'skills.id', '=', 'skill_discounts.discounting_skill')
            ->join('character_skills', 'discounting_skill', '=', 'character_skills.skill_id')
            ->where('character_skills.character_id', $this->character_id)
            ->where('character_skills.completed', true)
            ->where('character_skills.discount_used', false)
            ->select('skills.name', 'character_skills.id', 'skill_discounts.discount')
            ->get();
    }

    public function discountFor($discountedSkillId): int
    {
        $discount = SkillDiscount::where('discounting_skill', $this->skill_id)
            ->where('discounted_skill', $discountedSkillId)
            ->first();
        return $discount->discount ?? 0;
    }

    public function getLevelAttribute(): int
    {
        if ($this->skill->repeatable) {
            return $this->character->trainedSkills->where('skill_id', $this->skill_id)->count();
        }
        return 0;
    }
}
