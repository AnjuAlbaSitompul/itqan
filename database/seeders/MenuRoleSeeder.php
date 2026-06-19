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
                'Performance',
                'Organization',
                'Karyawan',
                'Approval & Report',
                'Kehadiran',
            ],
            'spv_hc' => [
                'Dashboard',
                'Task',
                'Team Management',
                'Performance',
                'Organization',
                'Karyawan',
                'Approval',
                'Approval & Report',
                'Kehadiran',
            ],
            'manager_hc' => [
                'Dashboard',
                'Task',
                'Team Management',
                'Performance',
                'Organization',
                'Karyawan',
                'Approval',
                'Approval & Report',
                'Kehadiran',
            ],
            'admin_hc' => [
                'Dashboard',
                'Task',
                'Performance',
                'Organization',
                'Karyawan',
                'Kehadiran',
            ],
            'spv' => [
                'Dashboard',
                'Task',
                'Team Management',
            ],
            'manager' => [
                'Dashboard',
                'Task',
                'Team Management',
                'Approval',
            ],
            'direksi' => [
                'Dashboard',
                'Task',
                'Team Management',
                'Approval',
            ],
            'pegawai' => [
                'Dashboard',
                'Task',
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
