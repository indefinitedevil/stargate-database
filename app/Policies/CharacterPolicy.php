<?php

namespace App\Policies;

use App\Models\Character;
use App\Models\Event;
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
     * Determine whether the user can view any models.
     */
    public function viewAll(User $user): Response
    {
        return $user->can('view all characters')
            ? Response::allow()
            : Response::deny('You are not authorized to view all characters.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Character $character): Response
    {
        $view = false;
        if ($user->can('view all characters')) {
            $view = true;
        } else if ($user->can('view own character') && $user->id === $character->user_id) {
            $view = true;
        }
        return $view
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewSkills(User $user): Response
    {
        if ($user->can('view skill breakdown')) {
            $view = true;
        } else {
            $view = $user->events()->where('end_date', '>', now())->wherePivot('role', Event::ROLE_RUNNER)->count() > 0;
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
        return $user->can('create character')
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
        } else if ($user->can('edit own character') && $user->id === $character->user_id) {
            $update = !in_array($character->status_id, [Status::DEAD, Status::RETIRED]);
        }
        return $update
            ? Response::allow()
            : Response::deny('You are not authorized to update this character.');
    }

    public function approve(User $user, Character $character): Response
    {
        return Status::READY == $character->status_id && $user->can('edit all characters')
            ? Response::allow()
            : Response::deny('You are not authorized to approve this character.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Character $character): Response
    {
        if ($character->status_id >= Status::PLAYED) {
            return Response::deny('Characters which have been played cannot be deleted.');
        } else if ($character->status_id >= Status::APPROVED && !$character->canBeReset()) {
            return Response::deny('Characters which have been played cannot be deleted.');
        }
        $delete = false;
        if ($user->can('delete all characters')) {
            $delete = true;
        } else if ($user->can('delete own character') && $user->id === $character->user_id) {
            $delete = true;
        }
        return $delete
            ? Response::allow()
            : Response::deny('You are not authorized to delete this character.');
    }

    /**
     * Determine whether the user can change the model status to inactive.
     */
    public function inactive(User $user, Character $character): Response
    {
        if ($character->status_id < Status::APPROVED) {
            return Response::deny('Characters which have not been played cannot be made inactive.');
        }
        $inactive = false;
        if ($user->can('delete all characters')) {
            $inactive = true;
        }
        return $inactive
            ? Response::allow()
            : Response::deny('You are not authorized to make this character inactive.');
    }

    /**
     * Determine whether the user can change the model status to inactive.
     */
    public function resuscitate(User $user, Character $character): Response
    {
        if ($character->status_id < Status::APPROVED) {
            return Response::deny('Characters which have not been played cannot be resuscitated.');
        } else if ($character->status_id > Status::PLAYED) {
            return Response::deny('Character cannot be resuscitated.');
        }
        $resuscitate = false;
        if ($user->can('edit all characters')) {
            $resuscitate = true;
        }
        return $resuscitate
            ? Response::allow()
            : Response::deny('You are not authorized to resuscitate this character.');
    }

    /**
     * Determine whether the user can change the model status to played.
     */
    public function played(User $user, Character $character): Response
    {
        if (!in_array($character->status_id, [Status::APPROVED, Status::INACTIVE])) {
            return Response::deny('Character cannot be marked as played.');
        }
        $played = false;
        if ($user->can('delete all characters')) {
            $played = true;
        }
        return $played
            ? Response::allow()
            : Response::deny('You are not authorized to change this character status.');
    }
}
