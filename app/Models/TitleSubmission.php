<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitleSubmission extends Model
{
    // fillable
    protected $fillable = [
        'thesis_id',
        'title',
        'description',
        'status',
        'note',
    ];

    public function thesis(): BelongsTo
    {
        return $this->belongsTo(Thesis::class);
    }
}
