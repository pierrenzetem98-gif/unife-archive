<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'created_by',
        'title',
        'academic_year',
        'type',
        'file_path',
        'is_community',
    ];

    protected function casts(): array
    {
        return [
            'is_community' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Supprime le PDF du disque quand l'examen est supprimé
        static::deleted(function (Exam $exam) {
            if ($exam->file_path) {
                Storage::disk('public')->delete($exam->file_path);
            }
        });
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Admin : tout. Étudiant : seulement ses propres dépôts. */
    public function canBeDeletedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $user->isAdmin() || (int) $this->created_by === (int) $user->id;
    }
}