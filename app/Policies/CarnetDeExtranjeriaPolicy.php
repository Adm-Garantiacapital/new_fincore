<?php

namespace App\Policies;

use App\Models\CarnetDeExtranjeria;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CarnetDeExtranjeriaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver carnet de extrangeria');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CarnetDeExtranjeria $carnetDeExtranjeria): bool
    {
        return $user->can('ver carnet de extrangeria');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('ver carnet de extrangeria');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CarnetDeExtranjeria $carnetDeExtranjeria): bool
    {
        return $user->can('editar carnet de extrangeria');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CarnetDeExtranjeria $carnetDeExtranjeria): bool
    {
        return $user->can('eliminar carnet de extrangeria');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CarnetDeExtranjeria $carnetDeExtranjeria): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CarnetDeExtranjeria $carnetDeExtranjeria): bool
    {
        return false;
    }
}
