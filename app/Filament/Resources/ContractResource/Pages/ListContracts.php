<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use App\Filament\Resources\ContractResource\Wigdets\ContractStatsOverview;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListContracts extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ContractResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ContractStatsOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
