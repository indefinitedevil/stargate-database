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

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class, 'character_character_traits');
    }
}
