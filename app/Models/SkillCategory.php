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

    const int TECHNOLOGY = 1;
    const int SCIENCE_SOCIAL = 2;
    const int COMPLEX = 3;
    const int BASIC = 4;
    const int COMBAT = 5;
    const int ALIEN = 6;
    const int SYSTEM = 7;
    const int REMOVED = 8;

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }

    public function cleanSkills(): HasMany
    {
        return $this->skills()
            ->where('id' , '!=', Skill::ADDITIONAL_AA_SPEC)
            ->where('skill_category_id', '!=', SkillCategory::SYSTEM)
            ->orderBy('name');
    }
}
