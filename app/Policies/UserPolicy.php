<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAll(User $user): Response
    {
        return $user->can('view all users')
            ? Response::allow()
            : Response::deny('You are not authorized to view this user.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): Response
    {
        return $user->can('view all users') || $user->id === $model->id
            ? Response::allow()
            : Response::deny('You are not authorized to view this user.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->can('edit all users')
            ? Response::allow()
            : Response::deny('You are not authorized to create new users.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function edit(User $user, User $model): Response
    {
        return $user->can('edit all users') || $user->id === $model->id
            ? Response::allow()
            : Response::deny('You are not authorized to edit this user.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): Response
    {
        return $user->can('edit all users') || $user->id === $model->id
            ? Response::allow()
            : Response::deny('You are not authorized to delete this user.');
    }
}
