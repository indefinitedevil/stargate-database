<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property Collection cards
 * @property Collection feats
 * @property int skill_category_id
 * @property bool upkeep
 * @property int cost
 * @property int specialties
 * @property bool repeatable
 * @property int body
 * @property int vigor
 * @property bool display
 */
class Skill extends Model
{
    use HasFactory;

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(CardType::class)
            ->withPivot('number', 'total');
    }

    public function feats(): BelongsToMany
    {
        return $this->belongsToMany(Feat::class);
    }

    public function skillCategory(): HasOne
    {
        return $this->hasOne(SkillCategory::class);
    }

    public function specialtyType(): HasOne
    {
        return $this->hasOne(SpecialtyType::class);
    }

    public function specialties(): HasMany
    {
        return $this->hasMany(SkillSpecialty::class);
    }

    public function characters(): HasManyThrough
    {
        return $this->hasManyThrough(Character::class, CharacterSkill::class);
    }
}
