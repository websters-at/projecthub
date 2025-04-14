<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('contracts')->insert([
                'name' => "Projekt $i",
                'priority' => ['low', 'mid', 'high'][rand(0, 2)],
                'customer_id' => $i,
                'description' => "Beschreibung zu Projekt $i",
                'zip_code' => '101' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'is_finished' => $i % 2 === 0,
                'city' => 'Berlin',
                'state' => 'Berlin',
                'due_to' => now()->addDays(rand(10, 90)),
                'country' => 'Deutschland',
                'attachments' => null,
                'address' => "Projektstraße $i",
                'address2' => "Etage $i",
                'address3' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
