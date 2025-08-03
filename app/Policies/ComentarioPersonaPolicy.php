<?php

namespace App\Policies;

use App\Models\ComentarioPersona;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ComentarioPersonaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver comentario persona');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ComentarioPersona $comentarioPersona): bool
    {
        return $user->can('ver comentario persona');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear comentario persona');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ComentarioPersona $comentarioPersona): bool
    {
        return $user->can('editar comentario persona');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ComentarioPersona $comentarioPersona): bool
    {
        return $user->can('eliminar comentario persona');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ComentarioPersona $comentarioPersona): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ComentarioPersona $comentarioPersona): bool
    {
        return false;
    }
}
