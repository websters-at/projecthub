<?php

namespace App\Filament\Resources\GeneralSoftwareCredentialsResource\Pages;

use App\Filament\Resources\GeneralSoftwareCredentialsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGeneralSoftwareCredentials extends ViewRecord
{
    protected static string $resource = GeneralSoftwareCredentialsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
