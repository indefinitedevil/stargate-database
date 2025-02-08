<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property string name
 * @property string print_name
 * @property string description
 * @property Collection cards
 * @property Collection feats
 * @property Collection specialtyList
 * @property int specialty_type_id
 * @property SpecialtyType specialtyType
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

    const LEADERSHIP = 35;
    const LEADERSHIP_EXTRA_PERSON = 45;

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

    public function category(): BelongsTo
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

    public function backgrounds(): BelongsToMany
    {
        return $this->belongsToMany(Background::class);
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
        static $completedCategorySkills = [];
        if ($character && $category->scaling) {
            if (empty($completedCategorySkills[$category->id])) {
                $completedCategorySkills[$category->id] = $character->trainedSkills()
                    ->where('skills.skill_category_id', $this->skill_category_id);
            }
            $countSkills = $completedCategorySkills[$category->id]->count();
            return $category->cost + $countSkills;
        }
        return $category->cost;
    }

    public function prereqs(): HasMany
    {
        return $this->hasMany(SkillPrereq::class);
    }

    public function unlocks(): HasMany
    {
        return $this->hasMany(SkillPrereq::class, 'prereq_id', 'id');
    }

    public function locks(): HasMany
    {
        return $this->hasMany(SkillLockout::class, 'skill_id', 'id');
    }

    public function lockedBy(): HasMany
    {
        return $this->hasMany(SkillLockout::class, 'lockout_id', 'id');
    }
}
