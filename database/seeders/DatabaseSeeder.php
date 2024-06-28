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
            ['name' => 'Create Contracts'],
            ['name' => 'View Contracts'],
            ['name' => 'Delete Contracts'],
            ['name' => 'Restore Contracts'],
            ['name' => 'Update Contracts'],
            ['name' => 'View All Contracts'],
            ['name' => 'View Special Contract Filters'],

            ['name' => 'Create Customers'],
            ['name' => 'View Customers'],
            ['name' => 'Restore Customers'],
            ['name' => 'Delete Customers'],
            ['name' => 'Update Customers'],
            ['name' => 'View Special Customer Filters'],

            ['name' => 'Create Times'],
            ['name' => 'View Times'],
            ['name' => 'Restore Times'],
            ['name' => 'Delete Times'],
            ['name' => 'Update Times'],
            ['name' => 'View All Times'],
            ['name' => 'View Special Customer Times'],


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
