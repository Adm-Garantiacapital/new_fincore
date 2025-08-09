<?php

namespace App\Policies;

use App\Models\PersonaCondicionFinanciera;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PersonaCondicionFinancieraPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver condiciones financieras');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonaCondicionFinanciera $personaCondicionFinanciera): bool
    {
        return $user->can('ver condiciones financieras');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear condiciones financieras');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersonaCondicionFinanciera $personaCondicionFinanciera): bool
    {
        return $user->can('editar condiciones financieras');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonaCondicionFinanciera $personaCondicionFinanciera): bool
    {
        return $user->can('eliminar condiciones financieras');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PersonaCondicionFinanciera $personaCondicionFinanciera): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PersonaCondicionFinanciera $personaCondicionFinanciera): bool
    {
        return false;
    }
}
