<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
       |--------------------------------------------------------------------------
       | DASHBOARD
       |--------------------------------------------------------------------------
       */

        Menu::create([
            'name' => 'Dashboard',
            'icon' => 'fe fe-home',
            'route' => 'dashboard',
            'parent_id' => null,
            'sort' => 1,
            'is_active' => 1,
        ]);



        // KPI
        $kpi = Menu::create([
            'name' => 'KPI',
            'icon' => 'fe fe-clipboard',
            'route' => null,
            'parent_id' => null,
            'sort' => 2,
            'is_active' => 1,
        ]);

        Menu::create([
            'name' => 'KPI Period',
            'icon' => 'fe fe-target',
            'route' => 'key.performance.indicator',
            'parent_id' => $kpi->id,
            'sort' => 1,
            'is_active' => 1,
        ]);

        // Organization

        $organization = Menu::create([
            'name' => 'Organization',
            'icon' => 'fe fe-briefcase',
            'route' => null,
            'parent_id' => null,
            'sort' => 3,
            'is_active' => 1,
        ]);
        Menu::create([
            'name' => 'Structure Organisasi',
            'icon' => 'fe fe-sitemap',
            'route' => 'organization.structure',
            'parent_id' => $organization->id,
            'sort' => 1,
            'is_active' => 1,
        ]);

        Menu::create([
            'name' => 'Outlet',
            'icon' => 'fe fe-map-pin',
            'route' => 'master.outlet',
            'parent_id' => $organization->id,
            'sort' => 2,
            'is_active' => 1,
        ]);
        Menu::create([
            'name' => 'Jabatan',
            'icon' => 'fe fe-user-check',
            'route' => 'master.jabatan',
            'parent_id' => $organization->id,
            'sort' => 3,
            'is_active' => 1,
        ]);

        Menu::create([
            'name' => 'Unit',
            'icon' => 'fe fe-layers',
            'route' => 'master.unit',
            'parent_id' => $organization->id,
            'sort' => 4,
            'is_active' => 1,
        ]);


        $karyawans = Menu::create([
            'name' => 'Karyawan',
            'icon' => 'fe fe-users',
            'route' => null,
            'parent_id' => null,
            'sort' => 4,
            'is_active' => 1,
        ]);

        Menu::create([
            'name' => 'Table Karyawan',
            'icon' => 'fe fe-users',
            'route' => 'master.users',
            'parent_id' => $karyawans->id,
            'sort' => 1,
            'is_active' => 1,
        ]);
        Menu::create([
            'name' => 'Catatan Teguran',
            'icon' => 'fe fe-user-check',
            'route' => 'user.request',
            'parent_id' => $karyawans->id,
            'sort' => 2,
            'is_active' => 1,
        ]);

        Menu::create([
            'name' => 'Pengunduran Diri',
            'icon' => 'fe fe-user-check',
            'route' => 'user.request',
            'parent_id' => $karyawans->id,
            'sort' => 3,
            'is_active' => 1,
        ]);



        Menu::create([
            'name' => 'Permintaan Login',
            'icon' => 'fe fe-user-check',
            'route' => 'user.request',
            'parent_id' => $karyawans->id,
            'sort' => 4,
            'is_active' => 1,
        ]);


        $kehadiran = Menu::create([
            'name' => 'Kehadiran',
            'icon' => 'fe fe-calendar',
            'route' => null,
            'parent_id' => null,
            'sort' => 5,
            'is_active' => 1,
        ]);

        Menu::create([
            'name' => 'Absensi',
            'icon' => 'fe fe-calendar-check',
            'route' => 'attendance',
            'parent_id' => $kehadiran->id,
            'sort' => 1,
            'is_active' => 1,
        ]);

        $task = Menu::create([
            'name' => 'Task',
            'icon' => 'fe fe-list',
            'route' => null,
            'parent_id' => null,
            'sort' => 6,
            'is_active' => 1,
        ]);

        Menu::create([
            'name' => 'KPI',
            'icon' => 'fe fe-list',
            'route' => 'task.kpi',
            'parent_id' => $task->id,
            'sort' => 1,
            'is_active' => 1,
        ]);
        Menu::create([
            'name' => 'IDP',
            'icon' => 'fe fe-list',
            'route' => 'task.idp',
            'parent_id' => $task->id,
            'sort' => 2,
            'is_active' => 1,
        ]);

        $team = Menu::create([
            'name' => 'Team',
            'icon' => 'fe fe-users',
            'route' => null,
            'parent_id' => null,
            'sort' => 7,
            'is_active' => 1,
        ]);

        Menu::create([
            'name' => 'Team KPI',
            'icon' => 'fe fe-users',
            'route' => 'team.kpi',
            'parent_id' => $team->id,
            'sort' => 1,
            'is_active' => 1,
        ]);

        Menu::create([
            'name' => 'Team IDP',
            'icon' => 'fe fe-users',
            'route' => 'team.idp',
            'parent_id' => $team->id,
            'sort' => 2,
            'is_active' => 1,
        ]);






        /*
        |--------------------------------------------------------------------------
        | SETTINGS
        |--------------------------------------------------------------------------
        */

        $settings = Menu::create([
            'name' => 'Settings',
            'icon' => 'fe fe-settings',
            'route' => null,
            'parent_id' => null,
            'sort' => 3,
            'is_active' => 1,
        ]);

        Menu::create([
            'name' => 'Role Permissions',
            'icon' => 'fe fe-shield',
            'route' => 'settings.role-permissions',
            'parent_id' => $settings->id,
            'sort' => 1,
            'is_active' => 1,
        ]);


    }
}
