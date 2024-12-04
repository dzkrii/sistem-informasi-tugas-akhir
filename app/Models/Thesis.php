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
        return $this->hasOne(ChapterStatus::class);
    }

    public function createInitialChapters()
    {
        // Buat 5 chapter status untuk thesis ini
        // for ($i = 1; $i <= 5; $i++) {
        $this->chapterStatuses()->create([
            'bab1' => 'not_started',
            'note1' => null,
            'bab2' => 'not_started',
            'note2' => null,
            'bab3' => 'not_started',
            'note3' => null,
            'bab4' => 'not_started',
            'note4' => null,
            'bab5' => 'not_started',
            'note5' => null,
        ]);
        // }
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
