<?php

namespace App\Policies;

use App\Models\TitleSubmission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TitleSubmissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Semua role bisa melihat daftar TitleSubmission
        return in_array($user->role, ['admin', 'kaprodi', 'lecturer', 'student']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TitleSubmission $titleSubmission): bool
    {
        // Sama seperti viewAny
        return in_array($user->role, ['admin', 'kaprodi', 'lecturer', 'student']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Hanya student dan admin yang bisa membuat
        return in_array($user->role, ['admin', 'student']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TitleSubmission $titleSubmission): bool
    {
        // Admin dan lecturer dapat update
        return in_array($user->role, ['admin', 'lecturer', 'student']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TitleSubmission $titleSubmission): bool
    {
        // Hanya admin dan student yang bisa menghapus
        return in_array($user->role, ['admin', 'student']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TitleSubmission $titleSubmission): bool
    {
        // Hanya admin yang bisa force delete
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TitleSubmission $titleSubmission): bool
    {
        // Hanya admin yang bisa force delete
        return $user->role === 'admin';
    }
}
