<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property int id
 * @property string name
 * @property Collection skillSpecialties
 */
class SpecialtyType extends Model
{
    use HasFactory;

    public function skillSpecialties()
    {
        return $this->hasMany(SkillSpecialty::class);
    }
}
