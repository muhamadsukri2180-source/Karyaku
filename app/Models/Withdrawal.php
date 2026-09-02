<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    protected $table = 'withdrawals';
    protected $primaryKey = 'id_withdrawal';

    protected $fillable = [
        'user_id',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'amount',
        'status', // pending, processed, rejected
        'notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function setBankAccountNumberAttribute($value)
    {
        if (!empty($value) && !str_starts_with((string) $value, '$2y$') && !str_starts_with((string) $value, '$2a$')) {
            $this->attributes['bank_account_number'] = \Illuminate\Support\Facades\Hash::make($value);
        } else {
            $this->attributes['bank_account_number'] = $value;
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by', 'id_user');
    }

    public function getKodeWithdrawalAttribute(): string
    {
        return 'WD-' . str_pad($this->id_withdrawal, 5, '0', STR_PAD_LEFT);
    }
}