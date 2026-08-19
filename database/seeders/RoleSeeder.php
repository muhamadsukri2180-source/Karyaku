<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_name' => 'admin', 'description' => 'Administrator sistem'],
            ['role_name' => 'verifikator', 'description' => 'Memverifikasi karya/kreator'],
            ['role_name' => 'penjual', 'description' => 'Kreator yang menjual jasa/karya'],
            ['role_name' => 'pembeli', 'description' => 'Pengguna yang membeli karya'],
            ['role_name' => 'customer_service', 'description' => 'Menangani laporan & bantuan pengguna'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['role_name' => $role['role_name']], $role);
        }
    }
}