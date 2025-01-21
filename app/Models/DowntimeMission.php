<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DowntimeMission extends Model
{
    use HasFactory;

    public function downtime(): BelongsTo
    {
        return $this->belongsTo(Downtime::class);
    }

    public function downtimeAction(): HasMany
    {
        return $this->hasMany(DowntimeAction::class);
    }
}
