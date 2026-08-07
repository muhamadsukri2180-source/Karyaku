<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerService extends Model
{
    use HasFactory;

    protected $table = 'customer_services';

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'status',
        'admin_note',
    ];

    // Relasi ke model User (Pengguna yang mengirim keluhan)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
}