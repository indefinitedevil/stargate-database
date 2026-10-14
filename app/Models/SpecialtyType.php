<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int id
 * @property string name
 * @property Collection|SkillSpecialty[] skillSpecialties
 * @property Collection|Skill[] skills
 */
class SpecialtyType extends Model
{
    public function skillSpecialties(): HasMany
    {
        if (auth()->user()?->can('edit skill specialty')) {
            return $this->hasMany(SkillSpecialty::class)->orderBy('name');
        }
        return $this->hasMany(SkillSpecialty::class)
            ->where('hidden', false)
            ->orderBy('name');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }
}
