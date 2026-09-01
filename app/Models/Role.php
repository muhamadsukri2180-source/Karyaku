<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Role extends Model
{
    protected $primaryKey = 'id_role';

    protected $fillable = [
        'role_name',
        'description'
    ];

    protected static function booted()
    {
        static::saving(function ($role) {
            if ($role->role_name) {
                $role->role_name = strtolower(trim($role->role_name));

                // Cek apakah ada role lain yang memiliki nama sama (case-insensitive)
                $exists = static::whereRaw('LOWER(trim(role_name)) = ?', [$role->role_name])
                    ->where('id_role', '!=', $role->id_role ?? 0)
                    ->exists();

                if ($exists) {
                    throw ValidationException::withMessages([
                        'role_name' => "Role dengan nama '{$role->role_name}' sudah ada dan tidak boleh duplikat."
                    ]);
                }
            }
        });
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_role', 'id_role');
    }
}

