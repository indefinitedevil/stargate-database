<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

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
 * @property Collection subSkills
 * @property Collection superSkills
 * @property Collection backgrounds
 */
class Skill extends Model
{
    use HasFactory;

    const ARCHEO_ANTHROPOLOGY = 9;
    const ADDITIONAL_AA_SPEC = 10;

    const LEADERSHIP = 35;
    const LEADERSHIP_EXTRA_PERSON = 45;

    const GENETICS = 13;
    const PATHOLOGY = 17;
    const MATHEMATICS = 21;

    const PLOT_CHANGE = 90;
    const SYSTEM_CHANGE = 94;

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
            if (empty($characterSkill)) {
                if (empty($completedCategorySkills[$category->id])) {
                    $completedCategorySkills[$category->id] = $character->trainedSkills()
                        ->where('skills.skill_category_id', $this->skill_category_id);
                }
                $countSkills = $completedCategorySkills[$category->id]->count();
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

    public function researchProjects(): BelongsToMany
    {
        return $this->belongsToMany(ResearchProject::class)
            ->withPivot('months');
    }

    /**
     * Skills which when taught can be improved by this one
     */
    public function subSkills()
    {
        return $this->hasManyThrough(Skill::class, SkillTraining::class, 'taught_skill_id', 'id', 'id', 'trained_skill_id');
    }

    /**
     * Skills which when taught can improve this one
     */
    public function superSkills()
    {
        return $this->hasManyThrough(Skill::class, SkillTraining::class, 'trained_skill_id', 'id', 'id', 'taught_skill_id');
    }

    public function abilities(): array
    {
        if (!empty($this->abilities)) {
            $abilities = explode(',', $this->abilities);
            return array_map('trim', $abilities);
        }
        return [];
    }
}
