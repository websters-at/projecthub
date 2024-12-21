<?php

namespace App\Policies;

use App\Models\CallNote;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CallNotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('View Call Notes');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CallNote $callNote): bool
    {
        return $user->hasPermissionTo('View Call Notes');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Create Call Notes');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CallNote $callNote): bool
    {
        return $user->hasPermissionTo('Update Call Notes');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CallNote $callNote): bool
    {
        return $user->hasPermissionTo('Delete Call Notes');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CallNote $callNote): bool
    {
        return $user->hasPermissionTo('Restore Call Notes');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CallNote $callNote): bool
    {
        return $user->hasPermissionTo('Delete Call Notes');
    }
}
