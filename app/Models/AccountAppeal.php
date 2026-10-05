<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountAppeal extends Model
{
    use HasFactory;

    protected $table = 'account_appeals';
    protected $primaryKey = 'id_appeal';

    protected $fillable = [
        'user_id',
        'reason',
        'proof_image',
        'status',
        'admin_note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'id_user');
    }
}
