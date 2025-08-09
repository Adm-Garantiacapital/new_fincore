<?php

namespace App\Policies;

use App\Models\ContactoPersona;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContactoPersonaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver contactos persona');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContactoPersona $contactoPersona): bool
    {
        return $user->can('ver contactos persona');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear contactos persona');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContactoPersona $contactoPersona): bool
    {
        return $user->can('editar contactos persona');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContactoPersona $contactoPersona): bool
    {
        return $user->can('eliminar contactos persona');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ContactoPersona $contactoPersona): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ContactoPersona $contactoPersona): bool
    {
        return false;
    }
}
