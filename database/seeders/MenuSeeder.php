<?php

namespace Database\Seeders;

use App\Models\menu;
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

        menu::create([
            'name' => 'Dashboard',
            'icon' => 'fe fe-home',
            'route' => 'dashboard',
            'parent_id' => null,
            'sort' => 1,
            'is_active' => 1,
        ]);


        menu::create([
            'name' => 'TPI',
            'icon' => 'fe fe-clipboard',
            'route' => 'tpi',
            'parent_id' => null,
            'sort' => 2,
            'is_active' => 1,
        ]);


        menu::create([
            'name' => 'IDP',
            'icon' => 'fe fe-package',
            'route' => 'idp',
            'parent_id' => null,
            'sort' => 3,
            'is_active' => 1,
        ]);

        $users = menu::create([
            'name' => 'User Management',
            'icon' => 'fe fe-users',
            'route' => null,
            'parent_id' => null,
            'sort' => 4,
            'is_active' => 1,
        ]);

        menu::create([
            'name' => 'User Request',
            'icon' => 'fe fe-user-check',
            'route' => 'user.request',
            'parent_id' => $users->id,
            'sort' => 1,
            'is_active' => 1,
        ]);
        menu::create([
            'name' => 'User Supervisor',
            'icon' => 'fe fe-user-check',
            'route' => 'user.supervisor',
            'parent_id' => $users->id,
            'sort' => 2,
            'is_active' => 1,
        ]);




        /*
        |--------------------------------------------------------------------------
        | MASTER
        |--------------------------------------------------------------------------
        */

        $master = menu::create([
            'name' => 'Master',
            'icon' => 'fe fe-database',
            'route' => null,
            'parent_id' => null,
            'sort' => 5,
            'is_active' => 1,
        ]);

        menu::create([
            'name' => 'Users',
            'icon' => 'fe fe-users',
            'route' => 'master.users',
            'parent_id' => $master->id,
            'sort' => 2,
            'is_active' => 1,
        ]);
        menu::create([
            'name' => 'Outlet',
            'icon' => 'fe fe-map-pin',
            'route' => 'master.outlet',
            'parent_id' => $master->id,
            'sort' => 3,
            'is_active' => 1,
        ]);
        menu::create([
            'name' => 'Jabatan',
            'icon' => 'fe fe-user-check',
            'route' => 'master.jabatan',
            'parent_id' => $master->id,
            'sort' => 4,
            'is_active' => 1,
        ]);

        menu::create([
            'name' => 'Unit',
            'icon' => 'fe fe-layers',
            'route' => 'master.unit',
            'parent_id' => $master->id,
            'sort' => 5,
            'is_active' => 1,
        ]);
        /*
        |--------------------------------------------------------------------------
        | SETTINGS
        |--------------------------------------------------------------------------
        */

        $settings = menu::create([
            'name' => 'Settings',
            'icon' => 'fe fe-settings',
            'route' => null,
            'parent_id' => null,
            'sort' => 3,
            'is_active' => 1,
        ]);

        menu::create([
            'name' => 'Role Permissions',
            'icon' => 'fe fe-shield',
            'route' => 'settings.role-permissions',
            'parent_id' => $settings->id,
            'sort' => 1,
            'is_active' => 1,
        ]);


    }
}
