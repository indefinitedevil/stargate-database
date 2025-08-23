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
 * @property Character|null team_lead
 * @property int team_lead_id
 * @property Character|null team_second
 * @property int team_second_id
 */
class Team extends Model
{
    use HasFactory;

    const LEAD = 1;
    const SECOND = 2;

    protected $fillable = [
        'name',
        'description',
        'event_id',
    ];

    protected $with = ['characters'];

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class)
            ->withPivot('position')
            ->orderBy('characters.name');
    }

    public function getViewRoute(): string
    {
        return route('teams.view', ['teamId' => $this, 'teamName' => Str::slug($this->name)]);
    }

    public function getTeamLeadAttribute(): ?Character
    {
        return $this->characters->where('pivot.position', self::LEAD)->first();
    }

    public function getTeamLeadIdAttribute(): int
    {
        return $this->team_lead?->id ?? 0;
    }

    public function getTeamSecondAttribute(): ?Character
    {
        return $this->characters->where('pivot.position', self::SECOND)->first();
    }

    public function getTeamSecondIdAttribute(): int
    {
        return $this->team_second?->id ?? 0;
    }

    public function getCharacterIdsAttribute(): array
    {
        return $this->characters->pluck('id')->toArray();
    }
}
