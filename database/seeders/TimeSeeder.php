<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 10) as $id) {
            foreach (range(1, 3) as $entry) {
                DB::table('times')->insert([
                    'contract_classification_id' => $id,
                    'description' => "Arbeitseinheit $entry für Klassifizierung $id",
                    'date' => now()->subDays(rand(1, 10)),
                    'total_hours_worked' => rand(1, 5),
                    'total_minutes_worked' => rand(0, 59),
                    'is_special' => rand(0, 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
