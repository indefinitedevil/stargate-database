<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

/**
 * @param int $id
 * @param string $name
 * @param int $specialty_type_id
 * @param int $hidden
 * @param SpecialtyType $specialtyType
 * @param CharacterSkill[]|Collection $characterSkills
 * @param ResearchProject[]|Collection $researchProjects
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
