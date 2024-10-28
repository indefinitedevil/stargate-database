<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property Character character
 * @property Skill skill
 * @property CharacterSkill discountUsedBy
 * @property CharacterSkill discountedBy
 * @property Collection skillSpecialties
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
}
