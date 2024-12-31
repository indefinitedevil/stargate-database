<?php

namespace App\Policies;

use App\Models\Character;
use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CharacterPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->can('view all characters') || $user->can('view own character')
            ? Response::allow()
            : Response::deny('You are not authorized to view this character.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Character $character): Response
    {
        $view = false;
        if ($user->can('view all characters')) {
            $view = true;
        }
        if ($user->can('view own character') && $user->id === $character->user_id) {
            $view = true;
        }
        return $view
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->can('create characters')
            ? Response::allow()
            : Response::deny('You are not authorized to create a character.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function edit(User $user, Character $character): Response
    {
        $update = false;
        if ($user->can('edit all characters')) {
            $update = true;
        }
        if ($user->can('edit own character') && $user->id === $character->user_id) {
            $update = !in_array($character->status_id, [Status::DEAD, Status::RETIRED]);
        }
        return $update
            ? Response::allow()
            : Response::deny('You are not authorized to update this character.');
    }

    public function approve(User $user, Character $character): Response
    {
        return $user->can('edit all characters')
            ? Response::allow()
            : Response::deny('You are not authorized to approve this character.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Character $character): Response
    {
        if ($character->status_id > Status::APPROVED) {
            return Response::deny('Characters which have been played cannot be deleted.');
        }
        $delete = false;
        if ($user->can('delete all characters')) {
            $delete = true;
        }
        if ($user->can('delete own character') && $user->id === $character->user_id) {
            $delete = true;
        }
        return $delete
            ? Response::allow()
            : Response::deny('You are not authorized to delete this character.');
    }
}
