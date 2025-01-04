<?php

namespace App\Policies;

use App\Models\Character;
use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SkillPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function add(User $user): Response
    {
        return $user->can('add skill')
            ? Response::allow()
            : Response::deny('You are not authorized to add skills.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function edit(User $user): Response
    {
        return $user->can('edit skill')
            ? Response::allow()
            : Response::deny('You are not authorized to edit skills.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Character $character): Response
    {
        return $user->can('delete skill')
            ? Response::allow()
            : Response::deny('You are not authorized to delete this character.');
    }
}
