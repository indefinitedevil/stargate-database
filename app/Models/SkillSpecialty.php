<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property int $specialty_type_id
 * @property boolean $hidden
 * @property SpecialtyType $specialtyType
 * @property CharacterSkill[]|Collection $characterSkills
 * @property ResearchProject[]|Collection $researchProjects
 */
class SkillSpecialty extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'specialty_type_id', 'hidden'];

    public function characterSkills(): BelongsToMany
    {
        return $this->belongsToMany(CharacterSkill::class);
    }

    public function specialtyType(): BelongsTo
    {
        return $this->belongsTo(SpecialtyType::class);
    }

    public function researchProjects(): BelongsToMany
    {
        return $this->belongsToMany(ResearchProject::class);
    }
}
