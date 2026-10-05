<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'memberships';

    protected $primaryKey = 'id_membership';

    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'max_upload',
        'benefit',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
        'max_upload' => 'integer',
    ];

    public function users()
    {
        return $this->hasMany(
            User::class,
            'id_membership',
            'id_membership'
        );
    }

    public function identityVerifications()
    {
        return $this->hasMany(
            IdentityVerification::class,
            'membership_id',
            'id_membership'
        );
    }
}
