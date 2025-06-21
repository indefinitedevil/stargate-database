<?php

namespace App\Listeners;

use App\Events\CharacterApproved;
use App\Models\Character;
use Random\RandomException;

class RollAtaGene
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CharacterApproved $event): void
    {
        self::roll($event->character);
    }

    public static function roll(Character $character): void
    {
        if ($character->ata_gene < 0) {
            try {
                $roll = random_int(1, 20);
            } catch (RandomException $e) {
                $roll = rand(1, 20);
            }
            if (20 == $roll) {
                $character->ata_gene = 1;
            } else {
                $character->ata_gene = 0;
            }
            $character->save();
        }
    }
}
