<?php

namespace App\Filament\Resources\ContractLoginCredentialsResource\Pages;

use App\Filament\Resources\ContractLoginCredentialsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContractLoginCredentials extends ListRecords
{
    protected static string $resource = ContractLoginCredentialsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
