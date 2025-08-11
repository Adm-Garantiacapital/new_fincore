<?php

namespace App\Policies;

use App\Models\Campo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CampoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver campos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Campo $campo): bool
    {
        return $user->can('ver campos');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear campos');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Campo $campo): bool
    {
        return $user->can('editar campos');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Campo $campo): bool
    {
        return $user->can('eliminar campos');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Campo $campo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Campo $campo): bool
    {
        return false;
    }
}
