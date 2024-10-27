<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Character extends Model
{
    use HasFactory;

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function background(): HasOne
    {
        return $this->hasOne(Background::class, 'id', 'background_id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(CharacterSkill::class)
            ->join('skills', 'character_skills.skill_id', '=', 'skills.id')
            ->orderBy('skills.name');
    }

    public function trainedSkills() {
        return $this->skills()->where('completed', true);
    }

    public function displayedTrainedSkills() {
        return $this->trainedSkills()->where('skills.display', true);
    }

    public function trainingSkills() {
        return $this->skills()->where('completed', false);
    }

    public function getFeatsAttribute()
    {
        $feats = $this->background->feats->all();
        foreach ($this->trainedSkills as $characterSkill) {
            if (!empty($characterSkill->skill->feats)) {
                $feats = array_merge($feats, $characterSkill->skill->feats->all());
            }
        }
        usort($feats, array($this, 'nameCompare'));
        return $feats;
    }

    public function getBodyAttribute() {
        $body = $this->background->body;
        foreach ($this->trainedSkills as $characterSkill) {
            $body += $characterSkill->skill->body;
        }
        return $body;
    }

    public function getVigorAttribute() {
        $vigor = $this->background->vigor;
        foreach ($this->trainedSkills as $characterSkill) {
            $vigor += $characterSkill->skill->vigor;
        }
        return $vigor;
    }

    public function getCardsAttribute() {
        $unsortedCards = [];
        foreach ($this->trainedSkills as $characterSkill) {
            $skillCards = $characterSkill->skill->cards;
            if (!empty($skillCards)) {
                foreach ($skillCards as $skillCard) {
                    if (!isset($unsortedCards[$skillCard->id][$skillCard->pivot->total])) {
                        $card = new \stdClass;
                        $card->name = $skillCard->name;
                        $card->number = $skillCard->pivot->number;
                        $unsortedCards[$skillCard->id][$skillCard->pivot->total] = $card;
                    } elseif ($skillCard->pivot->total) {
                        $unsortedCards[$skillCard->id][$skillCard->pivot->total]->number += $skillCard->pivot->number;
                    }
                }
            }
        }
        $cards = [];
        foreach ($unsortedCards as $unsortedCard) {
            if (!empty($unsortedCard[1])) {
                $cards[] = $unsortedCard[1];
            } else {
                $cards[] = $unsortedCard[0];
            }
        }
        usort($cards, array($this, 'nameCompare'));
        return $cards;
    }

    private function nameCompare($a, $b) {
        return strcmp($a->name, $b->name);
    }
}
