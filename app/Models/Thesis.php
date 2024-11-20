<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Thesis extends Model
{
    // fillable
    protected $fillable = [
        'student_id',
        'lecturer_id',
        'status',
    ];

    public function chapterStatuses()
    {
        return $this->hasMany(ChapterStatus::class);
    }

    public function createInitialChapters()
    {
        // Buat 5 chapter status untuk thesis ini
        for ($i = 1; $i <= 5; $i++) {
            $this->chapterStatuses()->create([
                'chapter_number' => $i,
                'status' => 'not_started',
                'revision_note' => null,
                'last_reviewed_at' => null
            ]);
        }
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function titleSubmissions()
    {
        return $this->hasMany(TitleSubmission::class);
    }
}
