<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
 * @property bool hidden
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

    const int ARCHEO_ANTHROPOLOGY = 9;
    const int ADDITIONAL_AA_SPEC = 10;

    const int LEADERSHIP = 35;
    const int LEADERSHIP_EXTRA_PERSON = 45;

    const int GENETICS = 13;
    const int PATHOLOGY = 17;
    const int ASTROPHYSICS = 11;
    const int MATHEMATICS = 21;

    const int PLOT_CHANGE = 90;
    const int SYSTEM_CHANGE = 94;

    protected $fillable = [
        'name',
        'print_name',
        'description',
        'specialty_type_id',
        'skill_category_id',
        'upkeep',
        'cost',
        'specialties',
        'repeatable',
        'body',
        'vigor',
        'display',
        'scaling',
        'abilities',
        'hidden',
    ];

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

    public function skillSpecialties(): HasManyThrough
    {
        if (auth()->user()?->can('edit skill specialty')) {
            return $this->hasManyThrough(SkillSpecialty::class, SpecialtyType::class, 'id', 'specialty_type_id', 'specialty_type_id', 'id');
        }
        return $this->hasManyThrough(SkillSpecialty::class, SpecialtyType::class, 'id', 'specialty_type_id', 'specialty_type_id', 'id')
            ->where('skill_specialit.hidden', false);
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

    public function cost(?Character $character = null, ?CharacterSkill $characterSkill = null): int
    {
        if ($this->attributes['cost']) {
            return $this->attributes['cost'];
        }
        $category = $this->skillCategory;
        static $completedCategorySkills = [];
        if ($character && $category->scaling) {
            if ($character->status_id >= Status::APPROVED || $characterSkill === null) {
                // This case covers approved characters and the available skills list on the add skill form
                if (!isset($completedCategorySkills[$character->id])) {
                    $completedCategorySkills[$character->id] = [];
                }
                if (!isset($completedCategorySkills[$character->id][$category->id])) {
                    $completedCategorySkills[$character->id][$category->id] = $character->trainedSkills()
                        ->where('skills.skill_category_id', $this->skill_category_id)->count();
                }
                $countSkills = $completedCategorySkills[$character->id][$category->id];
            } else {
                // This case covers trained skills on new characters
                // This is needed because they don't have saved costs yet, so they need to be calculated on the fly
                static $scalingCosts = [];
                if (!isset($scalingCosts[$character->id])) {
                    $scalingCosts[$character->id] = [];
                }
                if (!isset($scalingCosts[$character->id][$category->id])) {
                    $scalingCosts[$character->id][$category->id] = [];
                }
                if (!isset($scalingCosts[$character->id][$category->id][$this->id])) {
                    $scalingCosts[$character->id][$category->id][$this->id] = count(array_unique(array_diff_key($scalingCosts[$character->id][$category->id], [$this->id => 0])));
                }
                $countSkills = $scalingCosts[$character->id][$category->id][$this->id];
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

    public function characterCount($validCharacterIds) {
        return $this->characterSkills
            ->where('completed', true)
            ->where('removed', false)
            ->whereIn('character_id', $validCharacterIds)
            ->groupBy('character_id')
            ->count();
    }
}
