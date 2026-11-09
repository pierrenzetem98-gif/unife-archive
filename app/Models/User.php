<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /** Durée de validité d'un code d'activation (heures) */
    public const ACTIVATION_TTL_HOURS = 48;

    /** Nombre d'essais ratés avant blocage du code */
    public const MAX_ACTIVATION_ATTEMPTS = 5;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_approved',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'activation_code',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
            'activation_code_expires_at' => 'datetime',
            'activation_attempts' => 'integer',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isApproved(): bool
    {
        return (bool) $this->is_approved;
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'created_by');
    }

    /* ------------------------------------------------------------
     * Code d'activation (transmis par l'admin via Instagram)
     * ------------------------------------------------------------ */

    /**
     * Génère un nouveau code, le stocke haché et le renvoie en clair
     * (il ne pourra plus jamais être relu ensuite).
     */
    public function generateActivationCode(): string
    {
        // Sans 0/O, 1/I/L pour éviter les confusions à la lecture
        $alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $raw = '';
        for ($i = 0; $i < 6; $i++) {
            $raw .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        $this->forceFill([
            'activation_code' => Hash::make($raw),
            'activation_code_expires_at' => now()->addHours(self::ACTIVATION_TTL_HOURS),
            'activation_attempts' => 0,
        ])->save();

        return substr($raw, 0, 3).'-'.substr($raw, 3);
    }

    /**
     * 'none' | 'active' | 'expired' | 'locked'
     */
    public function activationStatus(): string
    {
        if (! $this->activation_code || ! $this->activation_code_expires_at) {
            return 'none';
        }
        if ($this->activation_code_expires_at->isPast()) {
            return 'expired';
        }
        if ($this->activation_attempts >= self::MAX_ACTIVATION_ATTEMPTS) {
            return 'locked';
        }
        return 'active';
    }

    public function checkActivationCode(string $input): bool
    {
        if ($this->activationStatus() !== 'active') {
            return false;
        }

        $normalized = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $input));

        return Hash::check($normalized, $this->activation_code);
    }

    /**
     * Approuve le compte et efface le code.
     */
    public function approve(): void
    {
        $this->forceFill([
            'is_approved' => true,
            'activation_code' => null,
            'activation_code_expires_at' => null,
            'activation_attempts' => 0,
        ])->save();
    }
}
