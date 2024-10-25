<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CharacterSkillSkillSpecialty extends Pivot
{
    public function characterSkill(): BelongsTo
    {
        return $this->belongsTo(CharacterSkill::class);
    }

    public function skillSpecialty(): BelongsTo
    {
        return $this->belongsTo(SkillSpecialty::class);
    }
}
