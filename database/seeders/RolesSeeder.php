<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Role::create(['name' => 'root']);
        Role::create(['name' => 'bakery']);
        Role::create(['name' => 'branch']);
        Role::create(['name' => 'vendor']);
    }
}
