<?php

namespace App\Filament\Resources\TodosResource\Pages;

use App\Filament\Resources\TodosResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTodos extends ViewRecord
{
    protected static string $resource = TodosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
