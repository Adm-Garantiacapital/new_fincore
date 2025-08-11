<?php

namespace App\Policies;

use App\Models\PersonaArchivo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PersonaArchivoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver subir archivos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonaArchivo $personaArchivo): bool
    {
        return $user->can('ver subir archivos');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear subir archivos');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersonaArchivo $personaArchivo): bool
    {
        return $user->can('editar subir archivos');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonaArchivo $personaArchivo): bool
    {
        return $user->can('eliminar subir archivos');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PersonaArchivo $personaArchivo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PersonaArchivo $personaArchivo): bool
    {
        return false;
    }
}
