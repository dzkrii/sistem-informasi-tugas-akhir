<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
