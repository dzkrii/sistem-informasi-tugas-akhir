<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChapterStatus extends Model
{
    // fillable
    protected $fillable = [
        'thesis_id',
        'bab1',
        'note1',
        'bab2',
        'note2',
        'bab3',
        'note3',
        'bab4',
        'note4',
        'bab5',
        'note5'
    ];

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }

    // Observer atau method untuk mengecek dan update status thesis
    public function checkAndUpdateThesisStatus()
    {
        // Cek apakah semua bab sudah accepted
        $allChaptersAccepted =
            $this->bab1 === 'accepted' &&
            $this->bab2 === 'accepted' &&
            $this->bab3 === 'accepted' &&
            $this->bab4 === 'accepted' &&
            $this->bab5 === 'accepted';

        if ($allChaptersAccepted) {
            // Update status thesis menjadi finished
            $this->thesis()->update([
                'status' => 'finished'
            ]);
        }
    }

    // Gunakan event boot untuk trigger setiap update
    protected static function booted()
    {
        // Setelah setiap update pada model
        static::updated(function ($chapterStatus) {
            $chapterStatus->checkAndUpdateThesisStatus();
        });
    }
}
