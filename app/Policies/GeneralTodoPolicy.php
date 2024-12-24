<?php

namespace App\Policies;

use App\Models\GeneralTodo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GeneralTodoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('View General Todos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GeneralTodo $generalTodo): bool
    {
        return $user->hasPermissionTo('View General Todos');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Create General Todos');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GeneralTodo $generalTodo): bool
    {
        return $user->hasPermissionTo('Update General Todos');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GeneralTodo $generalTodo): bool
    {
       return $user->hasPermissionTo('Delete General Todos');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GeneralTodo $generalTodo): bool
    {
        return $user->hasPermissionTo('Restore General Todos');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GeneralTodo $generalTodo): bool
    {
        return $user->hasPermissionTo('Delete General Todos');
    }
}
