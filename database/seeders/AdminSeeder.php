<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin' => [
                ['username' => 'admin', 'name' => 'Anju Alba Sitompul'],
            ],
            'manager' => [
                ['username' => 'manager1', 'name' => 'Manager 1'],
                ['username' => 'manager2', 'name' => 'Manager 2'],
                ['username' => 'manager3', 'name' => 'Manager 3'],
            ],
            'direksi' => [
                ['username' => 'direksi1', 'name' => 'Direksi 1'],
                ['username' => 'direksi2', 'name' => 'Direksi 2'],
                ['username' => 'direksi3', 'name' => 'Direksi 3'],
            ],
            'spv' => [
                ['username' => 'spv1', 'name' => 'Supervisor 1'],
                ['username' => 'spv2', 'name' => 'Supervisor 2'],
                ['username' => 'spv3', 'name' => 'Supervisor 3'],
            ],
            'pegawai' => [
                ['username' => 'pegawai1', 'name' => 'Pegawai 1'],
                ['username' => 'pegawai2', 'name' => 'Pegawai 2'],
                ['username' => 'pegawai3', 'name' => 'Pegawai 3'],
            ],
            'admin_hc' => [
                ['username' => 'adminhc1', 'name' => 'Admin HC 1'],
            ],
            'spv_hc' => [
                ['username' => 'spvhc1', 'name' => 'Supervisor HC 1'],
            ],
            'manager_hc' => [
                ['username' => 'managerhc1', 'name' => 'Manager HC 1'],
            ],
        ];

        foreach ($roles as $roleName => $users) {
            $roleId = Role::where('name', $roleName)->value('id');

            foreach ($users as $user) {
                User::firstOrCreate(
                    ['username' => $user['username']],
                    [
                        'name' => $user['name'],
                        'password' => Hash::make('123456'),
                        'role_id' => $roleId,
                    ]
                );
            }
        }
    }
}