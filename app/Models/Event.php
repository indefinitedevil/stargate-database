<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property string $start_date
 * @property string $end_date
 * @property Collection $users
 * @property Collection $characters
 * @property Downtime $downtime
 */
class Event extends Model
{
    use HasFactory;

    const ROLE_NONE = 0;
    const ROLE_PLAYER = 1;
    const ROLE_CREW = 2;
    const ROLE_RUNNER = 3;
    const ROLE_PAID_DOWNTIME = 4;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'location',
        'description',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('character_id', 'attended', 'role')
            ->where('role', '>', self::ROLE_NONE)
            ->orderBy('role')
            ->orderBy('name');
    }

    public function characters(): Collection
    {
        $users = $this->users()->where('role', self::ROLE_PLAYER)->get();
        return Character::whereIn('id', $users->pluck('pivot.character_id'))->get();
    }

    public static function roleName($role): string
    {
        return match ($role) {
            self::ROLE_PLAYER => 'Player',
            self::ROLE_CREW => 'Crew',
            self::ROLE_RUNNER => 'Runner',
            self::ROLE_PAID_DOWNTIME => 'Paid Downtime',
            self::ROLE_NONE => 'Did not attend',
            default => 'Unknown',
        };
    }

    public function downtime(): HasOne
    {
        return $this->hasOne(Downtime::class);
    }

    public static function nextEvent(): ?Event
    {
        return self::where('start_date', '>=', now())->orderBy('start_date', 'asc')->first();
    }

    public static function nextEventId(): int
    {
        $event = self::nextEvent();
        return $event ? $event->id : 0;
    }
}
