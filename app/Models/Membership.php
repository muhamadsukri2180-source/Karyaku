<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $primaryKey = 'id_membership';

    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'max_upload',
        'benefit'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_membership', 'id_membership');
    }
}
