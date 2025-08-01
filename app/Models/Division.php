<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property int id
 * @property string name
 * @property string|null description
 * @property Character[]|Collection characters
 * @property Department[]|Collection departments
 */
class Division extends Model
{
    use HasFactory;

    const HEAD = 1;
    const SECOND = 2;
    const STAFF = 3;

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class)
            ->withPivot('position');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function getViewRoute(): string
    {
        return route('divisions.view', ['divisionId' => $this, 'divisionName' => Str::slug($this->name)]);
    }
}
