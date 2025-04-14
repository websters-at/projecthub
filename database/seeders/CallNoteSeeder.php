<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CallNoteSeeder extends Seeder
{
    public function run(): void
    {
        $callIds = DB::table('calls')->pluck('id')->toArray();

        foreach ($callIds as $callId) {
            $noteCount = rand(1, 3); // 1 bis 3 Notizen pro Call

            for ($i = 1; $i <= $noteCount; $i++) {
                DB::table('call_notes')->insert([
                    'name' => "Notiz $i zu Call $callId",
                    'description' => "Detaillierte Notiz über besprochene Themen während des Anrufs $callId.",
                    'call_id' => $callId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
