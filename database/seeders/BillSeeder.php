<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BillSeeder extends Seeder
{
    public function run(): void
    {
        // IDs der vorhandenen ContractClassifications abrufen
        $classificationIds = DB::table('contract_classifications')->pluck('id')->toArray();

        foreach ($classificationIds as $id) {
            DB::table('bills')->insert([
                'contract_classification_id' => $id,
                'is_flat_rate' => $id % 2 === 0,
                'flat_rate_amount' => $id % 2 === 0 ? rand(500, 2000) : null,
                'name' => "Rechnung $id",
                'is_payed' => rand(0, 1),
                'hourly_rate' => $id % 2 === 0 ? null : rand(60, 100),
                'created_on' => now()->subDays(rand(1, 10)),
                'due_to' => now()->addDays(rand(5, 20)),
                'description' => "Rechnungsbeschreibung für Klassifizierung $id",
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
