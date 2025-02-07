<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    const ROLE_PLAYER = 1;
    const ROLE_CREW = 2;
    const ROLE_RUNNER = 3;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('character_id', 'attended', 'role');
    }

    public function characters(): Collection
    {
        $users = $this->users()->where('role', self::ROLE_PLAYER)->get();
        return Character::whereIn('id', $users->pluck('pivot.character_id'))->get();
    }
}
