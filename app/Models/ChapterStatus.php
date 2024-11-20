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
}
