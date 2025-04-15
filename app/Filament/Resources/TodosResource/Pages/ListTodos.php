<?php

namespace App\Filament\Resources\TodosResource\Pages;

use App\Filament\Resources\TimeResource\Pages\ListTimes;
use App\Filament\Resources\TimeResource\Widgets\TimesOverview;
use App\Filament\Resources\TodosResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListTodos extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = TodosResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            TodosResource\Widgets\TodosStatsOverview::class
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
