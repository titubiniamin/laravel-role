<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $dealerRole = Role::create(['name' => 'dealer']);

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all()); // Admin can do everything
        $dealerRole->givePermissionTo(['dealer.read', 'dealer.create']); // Dealer can create and read
    }
}
