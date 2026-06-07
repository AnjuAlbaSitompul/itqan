<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            ['name' => 'Direksi', 'level' => 1],
            ['name' => 'Manager', 'level' => 2],
            ['name' => 'Assistant Manager', 'level' => 3],
            ['name' => 'Supervisor', 'level' => 4],
            ['name' => 'Staff', 'level' => 5],
        ];

        foreach ($jabatans as $jabatan) {
            Jabatan::updateOrCreate(
                ['name' => $jabatan['name']],
                [
                    'level' => $jabatan['level'],
                    'is_active' => true,
                ]
            );
        }
    }
}
