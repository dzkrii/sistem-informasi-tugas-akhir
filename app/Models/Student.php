<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Student extends Model
{
    // fillable
    protected $fillable = [
        'name',
        'nim',
        'department_id',
    ];

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Menambahkan event deleting yang akan terpanggil sebelum student dihapus
        static::deleting(function ($student) {
            // Menghapus user yang terkait
            $student->user()->delete();
        });
    }

    public function thesis()
    {
        return $this->hasOne(Thesis::class);
    }
}
