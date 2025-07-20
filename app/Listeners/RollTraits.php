<?php

namespace App\Listeners;

use App\Events\CharacterApproved;
use App\Models\Character;
use App\Models\CharacterTrait;
use Random\RandomException;

class RollTraits
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
        static $traits = [];
        if (empty($traits)) {
            $traits = CharacterTrait::all();
        }
        if ($character->characterTraits->count() < $traits->count()) {
            foreach ($traits as $trait) {
                if (!$character->characterTraits->contains($trait->id)) {
                    $status = false;
                    if ($trait->chance > 0) {
                        try {
                            $roll = random_int(1, $trait->chance);
                        } catch (RandomException $e) {
                            $roll = rand(1, $trait->chance);
                        }
                        $status = $trait->chance == $roll;
                    }
                    $character->characterTraits()->attach($trait->id, ['status' => $status]);
                }
            }
            $character->resetIndicators();
        }
    }
}
