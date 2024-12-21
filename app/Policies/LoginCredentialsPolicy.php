<?php

namespace App\Policies;

use App\Models\LoginCredentials;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LoginCredentialsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('View Logins');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LoginCredentials $loginCredentials): bool
    {
        return $user->hasPermissionTo('View Logins');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Create Logins');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LoginCredentials $loginCredentials): bool
    {
        return $user->hasPermissionTo('Update Logins');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LoginCredentials $loginCredentials): bool
    {
        return $user->hasPermissionTo('Delete Logins');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LoginCredentials $loginCredentials): bool
    {
        return $user->hasPermissionTo('Restore Logins');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LoginCredentials $loginCredentials): bool
    {
        return $user->hasPermissionTo('Delete Logins');
    }
}
