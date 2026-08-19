<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdentityVerification extends Model
{
    use HasFactory;

    protected $table = 'identity_verifications';

    protected $primaryKey = 'id_identity_verification';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'verifier_id',
        'identity_document',
        'status',
        'notes',
        'verified_at',

        // DATA PENDAFTAR
        'nik',
        'address',

        // DATA REKENING
        'bank_name',
        'account_name',
        'account_number',

        // MEMBERSHIP
        'membership_id',

        // PEMBAYARAN
        'payment_proof',
        'payment_amount',
        'payment_submitted_at',

        // PROSES
        'submitted_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'payment_submitted_at' => 'datetime',
        'submitted_at' => 'datetime',
        'payment_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP KE USER
    |--------------------------------------------------------------------------
    */

   public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id_user'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFIKATOR (RELATIONSHIP KE VERIFIKATOR)
    |--------------------------------------------------------------------------
    */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verifier_id',
            'id_user'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MEMBERSHIP (RELATIONSHIP KE MEMBERSHIP)
    |--------------------------------------------------------------------------
    */
    public function membership(): BelongsTo
    {
        return $this->belongsTo(
            Membership::class,
            'membership_id',
            'id_membership'
        );
    }
}

