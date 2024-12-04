<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Kaprodi extends Model
{
    // fillable
    protected $fillable = [
        'name',
        'nidn',
        'department_id'
    ];

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
