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
 * @property int division_head_id
 * @property int division_second_id
 * @property int division_staff_id
 * @property Character|null division_head
 * @property Character|null division_second
 * @property Character|null division_staff
 * @property int[] character_ids
 */
class Division extends Model
{
    use HasFactory;

    const HEAD = 1;
    const SECOND = 2;
    const STAFF = 3;

    protected $fillable = [
        'name',
        'description',
    ];

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

    public function getDivisionHeadAttribute(): ?Character
    {
        return $this->characters()->wherePivot('position', self::HEAD)->first();
    }

    public function getDivisionHeadIdAttribute(): int
    {
        return $this->division_head?->id ?? 0;
    }

    public function getDivisionSecondAttribute(): ?Character
    {
        return $this->characters()->wherePivot('position', self::SECOND)->first();
    }

    public function getDivisionSecondIdAttribute(): int
    {
        return $this->division_second?->id ?? 0;
    }

    public function getDivisionStaffAttribute(): ?Character
    {
        return $this->characters()->wherePivot('position', self::STAFF)->first();
    }

    public function getDivisionStaffIdAttribute(): int
    {
        return $this->division_staff?->id ?? 0;
    }

    public function getCharacterIdsAttribute(): array
    {
        return $this->characters->pluck('id')->toArray();
    }
}
