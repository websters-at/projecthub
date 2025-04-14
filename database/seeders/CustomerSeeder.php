<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('customers')->insert([
                'full_name' => "Kunde $i",
                'company_name' => "Firma $i GmbH",
                'email' => "kunde$i@example.com",
                'phone' => '+49 171 12345' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'tax_id' => 'DE' . rand(100000000, 999999999),
                'address' => "Hauptstraße $i",
                'city' => 'Berlin',
                'state' => 'Berlin',
                'country' => 'Deutschland',
                'zip_code' => '101' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
