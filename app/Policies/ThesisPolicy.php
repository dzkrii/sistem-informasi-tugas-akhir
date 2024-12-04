<?php

namespace App\Policies;

use App\Models\Thesis;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ThesisPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Semua role bisa melihat (admin, kaprodi, lecturer, student)
        return in_array($user->role, ['admin', 'kaprodi']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Thesis $thesis): bool
    {
        // Sama seperti viewAny
        return in_array($user->role, ['admin', 'kaprodi']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Hanya admin dan kaprodi yang bisa create
        return in_array($user->role, ['admin', 'kaprodi']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Thesis $thesis): bool
    {
        // Hanya admin dan kaprodi yang bisa update
        return in_array($user->role, ['admin', 'kaprodi']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Thesis $thesis): bool
    {
        // Hanya admin dan kaprodi yang bisa delete
        return in_array($user->role, ['admin', 'kaprodi']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Thesis $thesis): bool
    {
        // Hanya admin yang bisa melakukan force delete
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Thesis $thesis): bool
    {
        // Hanya admin yang bisa melakukan force delete
        return $user->role === 'admin';
    }
}
