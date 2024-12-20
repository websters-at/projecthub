<?php

namespace App\Filament\Resources\ContractLoginCredentialsResource\Pages;

use App\Filament\Resources\LoginCredentialsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContractLoginCredentials extends EditRecord
{
    protected static string $resource = LoginCredentialsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
