<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['name' => 'dashboard.view', 'guard_name' => 'admin', 'group_name' => 'dashboard', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'dashboard.edit', 'guard_name' => 'admin', 'group_name' => 'dashboard', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin.create', 'guard_name' => 'admin', 'group_name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin.view', 'guard_name' => 'admin', 'group_name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin.edit', 'guard_name' => 'admin', 'group_name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin.delete', 'guard_name' => 'admin', 'group_name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin.approve', 'guard_name' => 'admin', 'group_name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'role.create', 'guard_name' => 'admin', 'group_name' => 'role', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'role.view', 'guard_name' => 'admin', 'group_name' => 'role', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'role.edit', 'guard_name' => 'admin', 'group_name' => 'role', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'role.delete', 'guard_name' => 'admin', 'group_name' => 'role', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'role.approve', 'guard_name' => 'admin', 'group_name' => 'role', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'profile.view', 'guard_name' => 'admin', 'group_name' => 'profile', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'profile.edit', 'guard_name' => 'admin', 'group_name' => 'profile', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'profile.delete', 'guard_name' => 'admin', 'group_name' => 'profile', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'profile.update', 'guard_name' => 'admin', 'group_name' => 'profile', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'dealer.create', 'guard_name' => 'admin', 'group_name' => 'dealer', 'created_at' => null, 'updated_at' => null],
            ['name' => 'dealer.view', 'guard_name' => 'admin', 'group_name' => 'dealer', 'created_at' => null, 'updated_at' => null],
            ['name' => 'dealer.edit', 'guard_name' => 'admin', 'group_name' => 'dealer', 'created_at' => null, 'updated_at' => null],
            ['name' => 'dealer.delete', 'guard_name' => 'admin', 'group_name' => 'dealer', 'created_at' => null, 'updated_at' => null],
        ];

        DB::table('permissions')->insert($permissions);
    }
}
