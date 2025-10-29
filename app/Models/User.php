<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /**
     * Check if user has admin access (admin, owner, seller)
     */
    public function hasAdminAccess(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_OWNER, self::ROLE_SELLER]);
    }
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Role constants
     */
    public const ROLE_ADMIN = 'admin';

    public const ROLE_CLIENT = 'client';

    public const ROLE_OWNER = 'owner';

    public const ROLE_SELLER = 'seller';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'province',
        'city',
        'role',
        'oauth_provider',
        'is_suspended',
        'suspended_until',
        'suspension_reason',
        'last_login_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'suspended_until' => 'datetime',
            'last_login_at' => 'datetime',
            'is_suspended' => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Productos creados por este usuario (si es administrador)
     */
    public function createdProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'user_id');
    }

    /**
     * Personalizaciones de productos hechas por este usuario (si es cliente)
     */
    public function productCustomizations(): HasMany
    {
        return $this->hasMany(ProductCustomization::class);
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Check if user is an administrator
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is a client
     */
    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    /**
     * Check if user is an owner
     */
    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    /**
     * Check if user is a seller
     */
    public function isSeller(): bool
    {
        return $this->role === self::ROLE_SELLER;
    }

    /**
     * Get all available roles
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_OWNER,
            self::ROLE_SELLER,
            self::ROLE_CLIENT,
        ];
    }

    // Account Status Methods

    /**
     * Check if user account is currently suspended
     */
    public function isSuspended(): bool
    {
        if (! $this->is_suspended) {
            return false;
        }

        // Check if suspension has expired
        if ($this->suspended_until && $this->suspended_until->isPast()) {
            $this->update([
                'is_suspended' => false,
                'suspended_until' => null,
                'suspension_reason' => null,
            ]);

            return false;
        }

        return true;
    }

    /**
     * Check if user can login (not suspended and email verified)
     */
    public function canLogin(): bool
    {
        return ! $this->isSuspended();
    }

    /**
     * Get account status as string
     */
    public function getAccountStatus(): string
    {
        if ($this->isSuspended()) {
            return 'Suspendido';
        }

        if (! $this->email_verified_at) {
            return 'Pendiente verificación';
        }

        return 'Activo';
    }

    /**
     * Get account status color for UI
     */
    public function getAccountStatusColor(): string
    {
        if ($this->isSuspended()) {
            return 'red';
        }

        if (! $this->email_verified_at) {
            return 'yellow';
        }

        return 'green';
    }

    /**
     * Suspend the user account for a specific period
     */
    public function suspend(\DateTimeInterface $until, ?string $reason = null): bool
    {
        return $this->update([
            'is_suspended' => true,
            'suspended_until' => $until,
            'suspension_reason' => $reason ?? 'Suspensión temporal por incumplimiento',
        ]);
    }

    /**
     * Remove suspension from user account
     */
    public function unsuspend(): bool
    {
        return $this->update([
            'is_suspended' => false,
            'suspended_until' => null,
            'suspension_reason' => null,
        ]);
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): bool
    {
        return $this->update(['last_login_at' => now()]);
    }
}
