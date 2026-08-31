<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id_user';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_role',
        'id_membership',
        'membership_expires_at',
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password'              => 'hashed',
        'membership_expires_at' => 'datetime',
    ];

    public function isMembershipActive(): bool
    {
        if (!$this->id_membership) {
            return false;
        }
        if (!$this->membership_expires_at) {
            return true; // default active if no expiry set
        }
        return $this->membership_expires_at->isFuture();
    }

    public function getRemainingDaysAttribute(): int
    {
        if (!$this->membership_expires_at) {
            return 0;
        }
        return max(0, (int) now()->diffInDays($this->membership_expires_at, false));
    }

    public function getMaxUploadLimit(): int
    {
        if ($this->isMembershipActive() && $this->membership) {
            return (int) ($this->membership->max_upload ?? 5);
        }
        return 5; // Default limit untuk akun gratis/tanpa membership/paket kadaluarsa
    }

    public function canUploadProduct(): bool
    {
        $max = $this->getMaxUploadLimit();
        $current = Product::where('seller_id', $this->id_user)->count();
        return $current < $max;
    }

    public function canUseAds(): bool
    {
        if (!$this->isMembershipActive()) {
            return false;
        }
        $name = strtolower($this->membership?->name ?? '');
        return str_contains($name, 'diamond') || str_contains($name, 'gold') || str_contains($name, 'platinum');
    }

    public function role()
    {
        return $this->belongsTo(
            Role::class,
            'id_role',
            'id_role'
        );
    }

    public function membership()
    {
        return $this->belongsTo(
            Membership::class,
            'id_membership',
            'id_membership'
        );
    }

    public function products()
    {
        return $this->hasMany(
            Product::class,
            'seller_id',
            'id_user'
        );
    }

    public function orders()
    {
        return $this->hasMany(
            Order::class,
            'buyer_id',
            'id_user'
        );
    }

    public function carts()
    {
        return $this->hasMany(
            Cart::class,
            'user_id',
            'id_user'
        );
    }

    public function wishlists()
    {
        return $this->hasMany(
            Wishlist::class,
            'user_id',
            'id_user'
        );
    }

    public function reports()
    {
        return $this->hasMany(
            Report::class,
            'user_id',
            'id_user'
        );
    }

    public function verificationsAsVerifier()
    {
        return $this->hasMany(
            Verification::class,
            'verifier_id',
            'id_user'
        );
    }

    public function identityVerification()
    {
        return $this->hasOne(
            IdentityVerification::class,
            'user_id',
            'id_user'
        );
    }

    public function identityVerificationsAsVerifier()
    {
        return $this->hasMany(
            IdentityVerification::class,
            'verifier_id',
            'id_user'
        );
    }


    public function identityVerifications()
    {
    return $this->hasMany(
        IdentityVerification::class,
        'user_id',
        'id_user'
    );
    }

}
