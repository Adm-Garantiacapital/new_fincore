<?php

namespace App\Policies;

use App\Models\DetallePersona;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DetallePersonaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver detalle persona');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DetallePersona $detallePersona): bool
    {
        return $user->can('ver detalle persona');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear detalle persona');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DetallePersona $detallePersona): bool
    {
        return $user->can('editar detalle persona');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DetallePersona $detallePersona): bool
    {
        return $user->can('eliminar detalle persona');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DetallePersona $detallePersona): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DetallePersona $detallePersona): bool
    {
        return false;
    }
}
