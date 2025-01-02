<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    const ROLE_PLAYER = 1;
    const ROLE_CREW = 2;
    const ROLE_RUNNER = 3;

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class)
            ->withPivot('attended', 'role')
            ->withTimestamps();
    }
}
