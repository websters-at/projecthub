<?php

namespace Database\Seeders;

use App\Models\ContractLoginCredentials;
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
       /* $user = User::factory()->create([
            'name' => 'Stevan Vlajic',
            'email' => 'stevan@webhoch.com',
            'password' => bcrypt('password')
        ]);

        $user2 = User::factory()->create([
            'name' => 'Jonathan Hochmeir',
            'email' => 'jonathan@webhoch.com',
            'password' => bcrypt('password')
        ]);

        $user3 = User::factory()->create([
            'name' => 'Jonas Fröller',
            'email' => 'jonas@webhoch.com',
            'password' => bcrypt('password')
        ]);

        $user4 = User::factory()->create([
            'name' => 'Michael Ruep',
            'email' => 'michael@webhoch.com',
            'password' => bcrypt('password')
        ]);*/

        // Create the Admin role
        $role = Role::create(['name' => 'Admin']);
        $mitarbeiterRole = Role::create(['name' => 'Mitarbeiter']);



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

            ['name' => 'View All Users'],
            ['name' => 'View Users'],
            ['name' => 'Update Users'],
            ['name' => 'Create Users'],
            ['name' => 'Delete Users'],

            ['name' => 'Root']
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
        $mitarbeiterRole->givePermissionTo([
            'View All Users',
            'Update Users',
            'View Bills',
            'Create Bills',
            'Update Bills',
            'Delete Bills',
            'Restore Bills',
            'View Contracts',
            'View Contract Notes',
            'View General Todos',
            'View Logins',
            'View Todos',
            'View Times',
            'Create Times',
            'Update Times',
            'Delete Times',
            'Restore Times',
            'Create Todos',
            'Delete Todos',
            'Update Todos',
            'Create Contract Notes',
            'Delete Contract Notes',
            'Update Contract Notes'
        ]);

        $allPermissions = Permission::all();

        $role->givePermissionTo($allPermissions);
        $users = [
            ['name' => 'Stevan Vlajic', 'email' => 'stevan@webhoch.com', 'is_admin' => false],
            ['name' => 'Jonathan Hochmeir', 'email' => 'jonathan@webhoch.com', 'is_admin' => true],
            ['name' => 'Michael Ruep', 'email' => 'michaelruep@webhoch.com', 'is_admin' => false],
            ['name' => 'Michael Schmidt', 'email' => 'michaelschmidt@webhoch.com', 'is_admin' => false],
            ['name' => 'Elias Reinhart', 'email' => 'eliasreinhart@webhoch.com', 'is_admin' => false],
            ['name' => 'Amer Besic', 'email' => 'amerbesic@webhoch.com', 'is_admin' => false],
            ['name' => 'Boffin Coders', 'email' => 'boffinconders@webhoch.com', 'is_admin' => false],
            ['name' => 'Deborah Benza', 'email' => 'deborahbenza@webhoch.com', 'is_admin' => false],
        ];

        foreach ($users as $userData) {
            $user = User::factory()->create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt('password'),
            ]);
            $user->assignRole($userData['is_admin'] ? $role : $mitarbeiterRole);
        }


        $this->call([
            CustomerSeeder::class,
            ContractSeeder::class,
            ContractClassificationSeeder::class,
            TimeSeeder::class,
            BillSeeder::class,
            CallSeeder::class,
            CallNoteSeeder::class,
            ContractLoginCredentialSeeder::class,
            ContractNoteSeeder::class,
        ]);
    }
}
