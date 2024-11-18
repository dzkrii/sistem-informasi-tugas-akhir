<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thesis extends Model
{
    // fillable
    protected $fillable = [
        'student_id',
        'lecturer_id',
        'status',
    ];
}
