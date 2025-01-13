<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property Collection characters
 * @property Collection skills
 * @property Collection feats
 * @property int body
 * @property int vigor
 * @property int months
 */
class Background extends Model
{
    use HasFactory;

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->orderBy('name');
    }

    public function feats(): BelongsToMany
    {
        return $this->belongsToMany(Feat::class)->orderBy('name');
    }
}
