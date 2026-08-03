<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdentityVerification extends Model
{
    protected $table = 'identity_verifications';
    protected $primaryKey = 'id_identity_verification';

    protected $fillable = [
        'user_id', 'verifier_id', 'identity_document',
        'status', 'notes', 'verified_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifier_id', 'id_user');
    }
}