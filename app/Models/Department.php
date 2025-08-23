<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property int id
 * @property string name
 * @property string|null description
 * @property Character[]|Collection characters
 * @property int division_id
 * @property Division division
 * @property Character|null department_head
 * @property int department_head_id
 * @property Collection department_specialists
 * @property int[] department_specialist_ids
 * @property int[] character_ids
 */
class Department extends Model
{
    use HasFactory;

    const HEAD = 1;
    const SPECIALIST = 2;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $with = ['characters'];

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class)
            ->withPivot('position');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function getViewRoute(): string
    {
        return route('departments.view', ['departmentId' => $this, 'departmentName' => Str::slug($this->name)]);
    }

    public function getDepartmentHeadAttribute(): ?Character
    {
        return $this->characters->where('pivot.position', self::HEAD)->first();
    }

    public function getDepartmentHeadIdAttribute(): int
    {
        return $this->department_head?->id ?? 0;
    }

    public function getDepartmentSpecialistsAttribute(): Collection
    {
        return $this->characters->where('pivot.position', self::SPECIALIST);
    }

    public function getDepartmentSpecialistIdsAttribute(): array
    {
        return $this->department_specialists->pluck('id')->toArray();
    }

    public function getCharacterIdsAttribute(): array
    {
        return $this->characters->pluck('id')->toArray();
    }
}
