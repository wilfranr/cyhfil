<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Maquina;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaquinaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_maquinas');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Maquina $maquina): bool
    {
        return $user->can('view_maquinas');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_maquinas');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Maquina $maquina): bool
    {
        return $user->can('update_maquinas');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Maquina $maquina): bool
    {
        return $user->can('delete_maquinas');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_maquinas');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Maquina $maquina): bool
    {
        return $user->can('force_delete_maquinas');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_maquinas');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Maquina $maquina): bool
    {
        return $user->can('restore_maquinas');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_maquinas');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Maquina $maquina): bool
    {
        return $user->can('replicate_maquinas');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_maquinas');
    }
}
