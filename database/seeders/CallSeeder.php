<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CallSeeder extends Seeder
{
    public function run(): void
    {
        $contractClassifications = DB::table('contract_classifications')->pluck('id')->toArray();

        foreach ($contractClassifications as $i => $classificationId) {
            DB::table('calls')->insert([
                'name' => "Call zu Klassifizierung #" . ($i + 1),
                'on_date' => Carbon::now()->subDays(rand(1, 30)),
                'description' => "Besprechung zum Projektstatus und offenen Aufgaben.",
                'is_done' => rand(0, 1),
                'contract_classification_id' => $classificationId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
