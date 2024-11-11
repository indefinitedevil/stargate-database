<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Skill discountedSkill
 * @property Skill discountingSkill
 * @property int discount
 */
class SkillDiscount extends Model
{
    use HasFactory;

    public function discountedSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'discounted_skill');
    }

    public function discountingSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'discounting_skill');
    }
}
