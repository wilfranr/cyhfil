<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tercero;
use Illuminate\Auth\Access\HandlesAuthorization;

class TerceroPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_terceros');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tercero $tercero): bool
    {
        return $user->can('view_terceros');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_terceros');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tercero $tercero): bool
    {
        return $user->can('update_terceros');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tercero $tercero): bool
    {
        return $user->can('delete_terceros');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_terceros');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Tercero $tercero): bool
    {
        return $user->can('force_delete_terceros');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_terceros');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Tercero $tercero): bool
    {
        return $user->can('restore_terceros');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_terceros');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Tercero $tercero): bool
    {
        return $user->can('replicate_terceros');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_terceros');
    }
}
