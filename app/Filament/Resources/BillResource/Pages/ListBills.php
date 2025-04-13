<?php

namespace App\Filament\Resources\BillResource\Pages;

use App\Filament\Resources\BillResource;
use App\Filament\Resources\BillResource\Widgets\BillStatsOverview;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListBills extends ListRecords
{
    use ExposesTableToWidgets;  // ← exposes the table/filter state to widgets

    protected static string $resource = BillResource::class;


    protected function getHeaderWidgets(): array
    {
        return [
            BillStatsOverview::class,
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
