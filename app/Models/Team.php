<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property int id
 * @property string name
 * @property string|null description
 * @property Character[]|Collection characters
 */
class Team extends Model
{
    use HasFactory;

    const LEAD = 1;
    const SECOND = 2;

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class)
            ->withPivot('position');
    }

    public function getViewRoute(): string
    {
        return route('teams.view', ['teamId' => $this, 'teamName' => Str::slug($this->name)]);
    }
}
