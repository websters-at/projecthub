<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users
        $user = User::factory()->create([
            'name' => 'Stevan Vlajic',
            'email' => 'stevanvlajic@webhoch.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Jonathan Hochmeir',
            'email' => 'jonathanhochmeir@webhoch.com',
        ]);

        // Create the Admin role
        $role = Role::create(['name' => 'Admin']);

        // Create permissions
        $permissions = [
            ['name' => 'Create Contract'],
            ['name' => 'View Contract'],
            ['name' => 'Delete Contract'],
            ['name' => 'Update Contract'],
            ['name' => 'Root']
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Retrieve all permissions
        $allPermissions = Permission::all();

        // Assign all permissions to the Admin role
        $role->givePermissionTo($allPermissions);

        // Assign the Admin role to the users
        $user->assignRole($role);
        $user2->assignRole($role);
    }
}
