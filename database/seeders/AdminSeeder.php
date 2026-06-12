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
        $users = [
            [
                'username' => 'admin',
                'name' => 'Administrator',
                'role' => 'admin',
            ],
            [
                'username' => 'manager',
                'name' => 'Manager',
                'role' => 'manager',
            ],
            [
                'username' => 'spv',
                'name' => 'Supervisor',
                'role' => 'spv',
            ],
            [
                'username' => 'pegawai',
                'name' => 'Pegawai',
                'role' => 'pegawai',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['username' => $user['username']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('123456'),
                    'role_id' => Role::where('name', $user['role'])->value('id'),
                ]
            );
        }
    }
}