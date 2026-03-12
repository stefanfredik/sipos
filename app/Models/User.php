<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use App\Traits\HasUlid;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasUlid;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_user',
        'username',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
            'two_factor_confirmed_at' => 'datetime',
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }
    
    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isBidan(): bool
    {
        return $this->role === UserRole::Bidan;
    }
    
    public function isKader(): bool
    {
        return $this->role === UserRole::Kader;
    }
    
    public function isPeserta(): bool
    {
        return $this->role === UserRole::Peserta;
    }
    
    /**
     * Get the kader profile associated with the user.
     */
    public function kader(): HasOne
    {
        return $this->hasOne(Kader::class);
    }

    /**
     * Get the bidan profile associated with the user.
     */
    public function bidan(): HasOne
    {
        return $this->hasOne(Bidan::class);
    }

    /**
     * Get the ibu_hamil profile associated with the user.
     */
    public function ibuHamil(): HasOne
    {
        return $this->hasOne(IbuHamil::class);
    }

    /**
     * Get the balita profile associated with the user.
     */
    public function balita(): HasOne
    {
        return $this->hasOne(Balita::class);
    }

    /**
     * Get the lansia profile associated with the user.
     */
    public function lansia(): HasOne
    {
        return $this->hasOne(Lansia::class);
    }
}
