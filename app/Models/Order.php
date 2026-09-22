<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id_order';

    protected $fillable = [
        'buyer_id',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
        'payment_proof',
        'payment_submitted_at',
        'verifier_id',
        'verified_at',
        'rejection_note',
    ];

    protected $casts = [
        'total_price'          => 'decimal:2',
        'payment_submitted_at' => 'datetime',
        'verified_at'           => 'datetime',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id', 'id_user');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifier_id', 'id_user');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id_order');
    }

    public function getKodeOrderAttribute(): string
    {
        return 'ORD-' . str_pad($this->id_order, 6, '0', STR_PAD_LEFT);
    }

    public function getPaymentProofUrlAttribute(): ?string
    {
        if (!$this->payment_proof) {
            return null;
        }
        if (str_starts_with($this->payment_proof, 'http://') || str_starts_with($this->payment_proof, 'https://')) {
            return $this->payment_proof;
        }
        return asset('storage/' . ltrim($this->payment_proof, '/'));
    }
}