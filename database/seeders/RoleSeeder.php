<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::firstOrCreate([
            'name' => 'admin',
        ]);

        $user = Role::firstOrCreate([
            'name' => 'user',
        ]);

        $admin->permissions()->sync(
            Permission::pluck('id')
        );
    }
}
