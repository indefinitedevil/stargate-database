<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DowntimeAction extends Model
{
    use HasFactory;

    public function downtime(): BelongsTo
    {
        return $this->belongsTo(Downtime::class);
    }

    public function actionType(): BelongsTo
    {
        return $this->belongsTo(ActionType::class);
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(CharacterSkill::class);
    }
}
