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
            ['email' => 'admin.secured@karyaku.com'], // Kunci pencarian berdasar email
            [
                'id_role'  => $adminRole->id_role,
                'name'     => 'Admin_KaryakuSecured#99',
                'password' => Hash::make('KaryakuAdmin#2026Secure!X9'),
                'phone'    => null,
                'avatar'   => null,
                'status'   => 'active',
            ]
        );

        $this->command->info('Admin user berhasil diperbarui: Admin_KaryakuSecured#99 / KaryakuAdmin#2026Secure!X9');
    }
}
