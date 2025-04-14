<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\ContractNote;
use Illuminate\Database\Seeder;

class ContractNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all contracts
        $contracts = Contract::all();

        foreach ($contracts as $contract) {
            // Create contract notes for each contract
            ContractNote::create([
                'contract_id' => $contract->id,
                'name' => 'Contract Note for #' . $contract->id,
                'date' => now(),
                'description' => 'Description for contract note #' . $contract->id,
                'attachments' => 'Attachment data for contract note #' . $contract->id,
                'note' => 'This is a sample note for contract #' . $contract->id,
                'original_filename' => 'note_file_' . $contract->id . '.txt',
            ]);
        }
    }
}
