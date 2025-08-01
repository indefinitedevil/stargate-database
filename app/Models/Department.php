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
 */
class Department extends Model
{
    use HasFactory;

    const HEAD = 1;
    const SPECIALIST = 2;

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
}
