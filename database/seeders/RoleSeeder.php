<?php

namespace Database\Seeders;

use App\Models\roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'pegawai',
            'spv',
            'manager',
            'admin',
            'admin_approve',
            'owner',
        ];

        foreach ($roles as $role) {
            roles::firstOrCreate([
                'name' => $role
            ]);
        }
    }
}
