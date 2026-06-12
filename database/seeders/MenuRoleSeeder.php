<?php

namespace Database\Seeders;

use App\Models\menu;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleMenus = [
            'admin' => [
                'Dashboard',
                'KPI',
                'Organization',
                'Karyawan',
                'Kehadiran',
            ],
            'spv' => [
                'Dashboard',
                'Task',
                'Team',
            ],
            'user' => [
                'Dashboard',
                'Kehadiran',
            ],
        ];

        foreach ($roleMenus as $roleName => $menuNames) {
            $role = Role::where('name', $roleName)->first();

            if (!$role) {
                continue;
            }

            $menus = menu::whereIn('name', $menuNames)->pluck('id');

            $role->menus()->syncWithoutDetaching($menus);
        }
    }
}
