<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CharacterTrait extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'chance',
        'revealed',
    ];

    protected $casts = [
        'revealed' => 'boolean',
    ];

    const TRAIT_MASKS = [
        'fa-atom-simple',
        'fa-shield-halved',
        'fa-puzzle-piece',
        'fa-sword',
        'fa-crown',
        'fa-eye-evil',
        'fa-dice-three',
        'fa-moon',
        'fa-balloon',
        'fa-ghost',
        'fa-duck',
        'fa-cupcake',
        'fa-tree',
        'fa-bell',
        'fa-star',
        'fa-heart',
        'fa-bolt',
        'fa-sparkles',
    ];

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class, 'character_character_traits');
    }
}
