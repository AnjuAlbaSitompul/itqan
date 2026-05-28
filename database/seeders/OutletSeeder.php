<?php

namespace Database\Seeders;

use App\Models\outlet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OutletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        outlet::factory()->count(10)->create();
    }
}
