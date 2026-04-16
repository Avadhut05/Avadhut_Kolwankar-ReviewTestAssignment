<?php

namespace App\Policies;

use App\Models\Instructor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InstructorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Instructor $instructor): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasRole('admin')
        ?Response::allow()
        :Response::deny('Only admins can create instructors.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function edit(User $user): Response
    {
        return $user->hasRole('admin')
        ?Response::allow()
        :Response::deny('Only admins can edit instructors.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): Response
    {
        return $user->hasRole('admin')
        ?Response::allow()
        :Response::deny('Only admins can delete instructors.');
    }
}
