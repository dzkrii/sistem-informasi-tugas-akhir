<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Kaprodi extends Model
{
    // fillable
    protected $fillable = [
        'name',
        'nidn',
    ];

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }
}
