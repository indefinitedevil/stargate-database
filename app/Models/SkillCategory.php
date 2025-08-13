<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int id
 * @property string name
 * @property bool scaling
 * @property int cost
 * @property Skill[]|Collection skills
 * @property Skill[]|Collection cleanSkills
 */
class SkillCategory extends Model
{
    use HasFactory;

    const TECHNOLOGY = 1;
    const SCIENCE_SOCIAL = 2;
    const COMPLEX = 3;
    const BASIC = 4;
    const COMBAT = 5;
    const ALIEN = 6;
    const SYSTEM = 7;

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }

    public function cleanSkills(): HasMany
    {
        return $this->skills()
            ->where('id' , '!=', Skill::ADDITIONAL_AA_SPEC)
            ->where('id', '!=', Skill::LEADERSHIP_EXTRA_PERSON)
            ->where('skill_category_id', '!=', SkillCategory::SYSTEM)
            ->orderBy('name');
    }
}
