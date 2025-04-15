<?php

namespace App\Filament\Resources\CallResource\Pages;

use App\Filament\Resources\CallResource;
use App\Filament\Resources\CallResource\Widgets\CallsStatsOverview;
use App\Filament\Resources\TimeResource\Widgets\TimesOverview;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListCalls extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = CallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            CallsStatsOverview::class
        ];
    }
}
