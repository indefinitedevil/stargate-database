<?php

namespace App\Listeners;

use App\Events\UserCreated;

class AssignPlayerRole
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
    public function handle(UserCreated $event): void
    {
        $event->user->assignRole('player');
    }
}
