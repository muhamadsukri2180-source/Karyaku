<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'session_id',
        'user_agent',
        'status',
        'reason',
        'last_activity',
        'request_count',
        'last_activity_at',
        'user_id',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}