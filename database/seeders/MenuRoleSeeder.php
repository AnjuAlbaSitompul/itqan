<?php

namespace Database\Seeders;

use App\Models\menu;
use App\Models\roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
|--------------------------------------------------------------------------
| GET ROLE ADMIN
|--------------------------------------------------------------------------
*/

        $admin = roles::where('name', 'admin')->first();

        if (!$admin) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | GET MENUS
        |--------------------------------------------------------------------------
        */

        $menus = menu::whereIn('name', [
            'Dashboard',
            'TPI',
            'IDP',
            'User Management',
            'Master',
        ])->pluck('id');

        /*
        |--------------------------------------------------------------------------
        | ATTACH MENU TO ADMIN
        |--------------------------------------------------------------------------
        */

        $admin->menus()->syncWithoutDetaching($menus);
    }
}
