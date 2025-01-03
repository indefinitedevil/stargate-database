<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @property int id
 * @property string name
 * @property Collection cards
 * @property Collection feats
 * @property Collection specialtyList
 * @property int skill_category_id
 * @property SkillCategory skillCategory
 * @property bool upkeep
 * @property int cost
 * @property int specialties
 * @property bool repeatable
 * @property int body
 * @property int vigor
 * @property bool display
 * @property bool scaling
 * @property Collection characterSkills
 */
class Skill extends Model
{
    use HasFactory;

    const ARCHEO_ANTHROPOLOGY = 9;
    const ADDITIONAL_AA_SPEC = 10;

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(CardType::class)
            ->withPivot('number', 'total');
    }

    public function feats(): BelongsToMany
    {
        return $this->belongsToMany(Feat::class);
    }

    public function skillCategory(): BelongsTo
    {
        return $this->belongsTo(SkillCategory::class);
    }

    public function specialtyType(): BelongsTo
    {
        return $this->belongsTo(SpecialtyType::class);
    }

    public function getSpecialtyListAttribute(): Collection
    {
        return $this->specialtyType->skillSpecialties;
    }

    public function characterSkills(): HasMany
    {
        return $this->hasMany(CharacterSkill::class);
    }

    public function discountedBy(): HasMany
    {
        return $this->hasMany(SkillDiscount::class, 'discounted_skill');
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(SkillDiscount::class, 'discounting_skill');
    }

    public function cost(Character $character = null, CharacterSkill $characterSkill = null): int
    {
        if ($this->attributes['cost']) {
            return $this->attributes['cost'];
        }
        $category = $this->skillCategory;
        if ($character && $category->scaling) {
            if (in_array($character->status_id, [Status::NEW, Status::READY])) {
                if (empty($characterSkill)) {
                    $countSkills = 0;
                } else {
                    static $scalingCosts = [];
                    if (empty($scalingCosts[$category->id])) {
                        $scalingCosts[$category->id] = [];
                    }
                    if (!isset($scalingCosts[$category->id][$this->id])) {
                        $scalingCosts[$category->id][$this->id] = count(array_unique(array_diff_key($scalingCosts[$category->id], [$this->id => 0])));
                    }
                    $countSkills = $scalingCosts[$category->id][$this->id];
                }
            } else {
                static $completedCategorySkills = [];
                if (empty($completedCategorySkills[$category->id])) {
                    $completedCategorySkills[$category->id] = $character->trainedSkills()
                        ->where('skills.skill_category_id', $this->skill_category_id);
                }
                $countSkills = $completedCategorySkills[$category->id]->count();
                if ($characterSkill) {
                    $countSkills = $completedCategorySkills[$category->id]->where('character_skills.id', '!=', $characterSkill->id)->count();
                }
            }
            return $category->cost + $countSkills;
        }
        return $category->cost;
    }
}
