<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_report';

    protected $fillable = [
        'user_id',
        'product_id',
        'reported_user_id',
        'reason',
        'description',
        'status',
        'admin_note',
        'action_taken',
        'reviewed_by',
        'reviewed_at',
    ];
    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id', 'id_user');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'id_user');
    }
}
