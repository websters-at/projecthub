<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       $user = User::factory()->create([
            'name' => 'Stevan Vlajic',
            'email' => 'stevanvlajic@webhoch.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Jonathan Hochmeir',
            'email' => 'jonathanhochmeir@webhoch.com',
        ]);

        $role = Role::create(['name' => 'Admin']);

        $user->assignRole($role);
        $user2->assignRole($role);
    }
}
