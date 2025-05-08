<?php

namespace App\Listeners;

use App\Events\CharacterApproved;
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
        if ($event->character->ata_gene < 0) {
            try {
                $roll = random_int(1, 20);
            } catch (RandomException $e) {
                $roll = rand(1, 20);
            }
            if (20 == $roll) {
                $event->character->ata_gene = 1;
            } else {
                $event->character->ata_gene = 0;
            }
            $event->character->save();
        }
    }
}
