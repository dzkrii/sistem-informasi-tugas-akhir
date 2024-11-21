<?php

namespace App\Policies;

use App\Models\ChapterStatus;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChapterStatusPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Semua role bisa melihat daftar ChapterStatus
        return in_array($user->role, ['admin', 'kaprodi', 'lecturer', 'student']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ChapterStatus $chapterStatus): bool
    {
        // Sama seperti viewAny
        return in_array($user->role, ['admin', 'kaprodi', 'lecturer', 'student']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Hanya admin yang bisa membuat
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ChapterStatus $chapterStatus): bool
    {
        // Admin dan lecturer bisa mengubah
        return in_array($user->role, ['admin', 'lecturer']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ChapterStatus $chapterStatus): bool
    {
        // Hanya admin yang bisa menghapus
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ChapterStatus $chapterStatus): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ChapterStatus $chapterStatus): bool
    {
        return $user->role === 'admin';
    }
}
