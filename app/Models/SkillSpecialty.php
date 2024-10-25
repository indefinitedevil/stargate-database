<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SkillSpecialty extends Model
{
    use HasFactory;

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function characterSkills(): BelongsToMany
    {
        return $this->belongsToMany(CharacterSkill::class);
    }

    public function specialtyType(): BelongsTo
    {
        return $this->belongsTo(SpecialtyType::class);
    }
}
