<?php

namespace App\Filament\Resources\ContractLoginCredentialsResource\Pages;

use App\Filament\Resources\ContractLoginCredentialsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContractLoginCredentials extends ViewRecord
{
    protected static string $resource = ContractLoginCredentialsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
