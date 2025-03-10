<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillTraining extends Model
{
    use HasFactory;

    protected $table = 'skill_training';

    public function taughtSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'taught_skill_id');
    }

    public function trainedSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'trained_skill_id');
    }
}
