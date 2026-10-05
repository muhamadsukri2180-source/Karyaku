<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('role_name', 'admin')->first();

        if (!$adminRole) {
            $this->command->error('Role admin belum ada. Jalankan RoleSeeder dulu.');
            return;
        }

        User::updateOrCreate(
            ['email' => 'admin@karyaku.com'], // kunci pencarian, biar tidak duplikat kalau di-seed ulang
            [
                'id_role'  => $adminRole->id_role,
                'name'     => 'admin',
                'password' => Hash::make('admin123'),
                'phone'    => null,
                'avatar'   => null,
                'status'   => 'active',
            ]
        );

        $this->command->info('Admin user berhasil dibuat: admin / admin123');
    }
}
