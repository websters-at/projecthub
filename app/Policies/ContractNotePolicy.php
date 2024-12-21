<?php

namespace App\Policies;

use App\Models\ContractNote;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContractNotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('View Contract Notes');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContractNote $contractNote): bool
    {
        return $user->hasPermissionTo('View Contract Notes');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Create Contract Notes');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContractNote $contractNote): bool
    {
        return $user->hasPermissionTo('Update Contract Notes');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContractNote $contractNote): bool
    {
        return $user->hasPermissionTo('Delete Contract Notes');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ContractNote $contractNote): bool
    {
        return $user->hasPermissionTo('Restore Contract Notes');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ContractNote $contractNote): bool
    {
        return $user->hasPermissionTo('Delete Contract Notes');
    }
}
