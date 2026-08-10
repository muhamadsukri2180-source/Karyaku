<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'reported_user_id',
        'reason',
        'description',
        'status',
        'admin_notes',
    ];

    /**
     * Relasi ke pelapor (User yang membuat laporan)
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Alias relasi user (sama dengan pelapor)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Relasi ke produk yang dilaporkan
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }

    /**
     * Relasi ke user/penjual yang dilaporkan
     */
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id', 'id_user');
    }
}
