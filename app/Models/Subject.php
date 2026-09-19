<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year',
        'description',
    ];

    /**
     * Relazione: una materia ha molti esami.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
}