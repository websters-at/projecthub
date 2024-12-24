<?php

namespace Database\Seeders;

use App\Models\User;
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
            'email' => 'stevan@webhoch.com',
            'password' => bcrypt('password')
        ]);

        $user2 = User::factory()->create([
            'name' => 'Jonathan Hochmeir',
            'email' => 'jonathan@webhoch.com',
            'password' => bcrypt('password')
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
            ['name' => 'View Special Contracts Filters'],

            ['name' => 'Create Customers'],
            ['name' => 'View Customers'],
            ['name' => 'Restore Customers'],
            ['name' => 'Delete Customers'],
            ['name' => 'Update Customers'],
            ['name' => 'View All Customers'],
            ['name' => 'View Special Customers Filters'],

            ['name' => 'Create Times'],
            ['name' => 'View Times'],
            ['name' => 'Restore Times'],
            ['name' => 'Delete Times'],
            ['name' => 'Update Times'],
            ['name' => 'View All Times'],
            ['name' => 'View Special Times Filters'],

            ['name' => 'Create Notes'],
            ['name' => 'View Notes'],
            ['name' => 'Restore Notes'],
            ['name' => 'Delete Notes'],
            ['name' => 'Update Notes'],
            ['name' => 'View All Notes'],
            ['name' => 'View Special Notes Filters'],

            ['name' => 'Create Bills'],
            ['name' => 'View Bills'],
            ['name' => 'Restore Bills'],
            ['name' => 'Delete Bills'],
            ['name' => 'Update Bills'],
            ['name' => 'View All Bills'],
            ['name' => 'View Special Bills Filters'],

            ['name' => 'Create Calls'],
            ['name' => 'View Calls'],
            ['name' => 'Restore Calls'],
            ['name' => 'Delete Calls'],
            ['name' => 'Update Calls'],
            ['name' => 'View All Calls'],
            ['name' => 'View Special Calls Filters'],

            ['name' => 'Create Todos'],
            ['name' => 'View Todos'],
            ['name' => 'Restore Todos'],
            ['name' => 'Delete Todos'],
            ['name' => 'Update Todos'],
            ['name' => 'View All Todos'],
            ['name' => 'View Special Todos Filters'],

            ['name' => 'Create Call Notes'],
            ['name' => 'View Call Notes'],
            ['name' => 'Restore Call Notes'],
            ['name' => 'Delete Call Notes'],
            ['name' => 'Update Call Notes'],
            ['name' => 'View All Call Notes'],
            ['name' => 'View Special Call Notes Filters'],

            ['name' => 'Create Contract Notes'],
            ['name' => 'View Contract Notes'],
            ['name' => 'Delete Contract Notes'],
            ['name' => 'Restore Contract Notes'],
            ['name' => 'Update Contract Notes'],
            ['name' => 'View All Contract Notes'],
            ['name' => 'View Special Contract Notes Filters'],

            ['name' => 'Create Logins'],
            ['name' => 'View Logins'],
            ['name' => 'Restore Logins'],
            ['name' => 'Delete Logins'],
            ['name' => 'Update Logins'],
            ['name' => 'View All Logins'],
            ['name' => 'View Special Logins Filters'],


            ['name' => 'Create General'],
            ['name' => 'View General'],
            ['name' => 'Restore General'],
            ['name' => 'Delete General'],
            ['name' => 'Update General'],
            ['name' => 'View All General'],
            ['name' => 'View Special General Filters'],

            ['name' => 'Create General Todos'],
            ['name' => 'View General Todos'],
            ['name' => 'Restore General Todos'],
            ['name' => 'Delete General Todos'],
            ['name' => 'Update General Todos'],
            ['name' => 'View All General Todos'],
            ['name' => 'View Special General Todos Filters'],



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
