<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property string name
 * @property bool scaling
 * @property int cost
 */
class SkillCategory extends Model
{
    use HasFactory;

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }
}
