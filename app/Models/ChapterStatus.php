<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChapterStatus extends Model
{
    // fillable
    protected $fillable = [
        'thesis_id',
        'chapter_number',
        'status',
        'note',
    ];

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }
}
