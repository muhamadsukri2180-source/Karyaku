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
        'password' => 'hashed',
    ];

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
