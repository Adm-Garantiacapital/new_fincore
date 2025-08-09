<?php

namespace App\Policies;

use App\Models\ProductoCampo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductoCampoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver campos productos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductoCampo $productoCampo): bool
    {
        return $user->can('ver campos productos');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear campos productos');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductoCampo $productoCampo): bool
    {
        return $user->can('editar campos productos');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductoCampo $productoCampo): bool
    {
        return $user->can('eliminar campos productos');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProductoCampo $productoCampo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProductoCampo $productoCampo): bool
    {
        return false;
    }
}
