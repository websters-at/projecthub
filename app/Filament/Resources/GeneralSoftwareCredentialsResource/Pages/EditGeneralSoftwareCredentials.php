<?php

namespace App\Filament\Resources\GeneralSoftwareCredentialsResource\Pages;

use App\Filament\Resources\GeneralSoftwareCredentialsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGeneralSoftwareCredentials extends EditRecord
{
    protected static string $resource = GeneralSoftwareCredentialsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
