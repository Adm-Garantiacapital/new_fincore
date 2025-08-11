<?php

namespace App\Policies;

use App\Models\Aceptante;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AceptantePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver aceptante');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Aceptante $aceptante): bool
    {
        return $user->can('ver aceptante');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear aceptante');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Aceptante $aceptante): bool
    {
        return $user->can('editar aceptante');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Aceptante $aceptante): bool
    {
        return $user->can('eliminar aceptante');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Aceptante $aceptante): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Aceptante $aceptante): bool
    {
        return false;
    }
}
