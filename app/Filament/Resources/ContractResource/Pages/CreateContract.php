<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use App\Models\ContractClassification;
use App\Models\User;
use Exception;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContract extends CreateRecord
{
    protected static string $resource = ContractResource::class;

    protected function getRedirectUrl(): string
    {
        return '/admin/contracts';
    }

}
