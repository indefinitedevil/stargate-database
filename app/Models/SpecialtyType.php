<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialtyType extends Model
{
    use HasFactory;

    public function skillSpecialties()
    {
        return $this->hasMany(SkillSpecialty::class);
    }
}
