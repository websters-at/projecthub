<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ContractClassificationSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray(); // Annahme: es gibt 4 Benutzer

        for ($i = 1; $i <= 10; $i++) {
            $userId = $userIds[($i - 1) % count($userIds)]; // Rundlauf über 4 User

            DB::table('contract_classifications')->insert([
                'contract_id' => $i,
                'user_id' => $userId,
                'hourly_rate' => rand(60, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
