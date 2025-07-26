<?php

namespace App\Policies;

use App\Models\OrdenTrabajo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrdenTrabajoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_orden::trabajo');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OrdenTrabajo $ordenTrabajo): bool
    {
        return $user->can('view_orden::trabajo');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_orden::trabajo');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OrdenTrabajo $ordenTrabajo): bool
    {
        return $user->can('update_orden::trabajo');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrdenTrabajo $ordenTrabajo): bool
    {
        return $user->can('delete_orden::trabajo');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OrdenTrabajo $ordenTrabajo): bool
    {
        return $user->can('restore_orden::trabajo');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OrdenTrabajo $ordenTrabajo): bool
    {
        return $user->can('force_delete_orden::trabajo');
    }
}
