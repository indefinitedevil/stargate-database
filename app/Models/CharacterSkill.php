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
 * @property Character character
 * @property Skill skill
 * @property int skill_id
 * @property bool discount_used
 * @property CharacterSkill discountUsedBy
 * @property CharacterSkill discountedBy
 * @property Collection skillSpecialties
 * @property Collection characterLogs
 * @property bool completed
 * @property int cost
 * @property int trained
 */
class CharacterSkill extends Model
{
    use HasFactory;

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

    public function discountedBy(): HasOne
    {
        return $this->hasOne(CharacterSkill::class, 'discount_used_by', 'id');
    }

    public function skillSpecialties(): BelongsToMany
    {
        return $this->belongsToMany(SkillSpecialty::class)
            ->orderBy('skill_specialties.name');
    }

    public function getSpecialtiesAttribute(): Collection
    {
        if (Skill::ARCHEO_ANTHROPOLOGY == $this->skill_id && $this->completed) {
            $additionalSpecialties = $this->character->skills()
                ->where('skill_id', Skill::ADDITIONAL_AA_SPEC)
                ->where('completed', true)
                ->getResults();
            foreach ($additionalSpecialties as $additionalSpecialty) {
                $this->skillSpecialties->concat($additionalSpecialty->skillSpecialties);
            }
        }
        return $this->skillSpecialties->sortBy('name');
    }

    public function getCostAttribute(): int
    {
        if ($this->completed) {
            return 0;
        }
        if ($this->skill->cost) {
            $cost = $this->skill->cost;
        } else {
            $cost = $this->skill->skillCategory->cost;
            if ($this->skill->skillCategory->scaling) {
                $completedCategorySkills = $this->character->trainedSkills()->where('skills.skill_category_id', $this->skill->skill_category_id)->count();
                $cost += $completedCategorySkills;
            }
        }
        if ($this->discountedBy) {
            $skillDiscounts = SkillDiscount::where('discounted_skill_id', $this->skill_id)
                ->where('discounting_skill_id', $this->discountedBy->skill_id)
                ->all();
            foreach ($skillDiscounts as $skillDiscount) {
                $cost -= $skillDiscount->discount;
            }
        }
        return $cost;
    }

    public function characterLogs(): HasMany
    {
        return $this->hasMany(CharacterLog::class);
    }

    public function getTrainedAttribute(): int
    {
        $trained = 0;
        foreach ($this->characterLogs as $characterLog) {
            $trained += $characterLog->amount_trained;
        }
        return $trained;
    }

    public function getNameAttribute(): string
    {
        if (1 == $this->skill->specialties) {
            if ($this->skillSpecialties->count()) {
                return $this->skill->name . ' (' . $this->skillSpecialties->first()->name . ')';
            }
            return $this->skill->name . ' (Not selected)';
        }
        return $this->skill->name;
    }
}
