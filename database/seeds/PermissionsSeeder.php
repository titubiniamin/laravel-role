<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        Permission::create(['name' => 'dealer.create']);
        Permission::create(['name' => 'dealer.read']);
        Permission::create(['name' => 'dealer.update']);
        Permission::create(['name' => 'dealer.delete']);
        // Add other permissions as needed
    }
}
