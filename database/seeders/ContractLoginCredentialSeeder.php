<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\ContractLoginCredentials;
use App\Models\LoginCredentials;
use Illuminate\Database\Seeder;

class ContractLoginCredentialSeeder extends Seeder
{
    /**
     */
    public function run(): void
    {
        $contracts = Contract::all();

        $loginCredentials = LoginCredentials::all();

        foreach ($contracts as $contract) {
            foreach ($loginCredentials as $loginCredential) {
                ContractLoginCredentials::create([
                    'contract_id' => $contract->id,
                    'login_credentials_id' => $loginCredential->id,
                ]);
            }
        }
    }
}
