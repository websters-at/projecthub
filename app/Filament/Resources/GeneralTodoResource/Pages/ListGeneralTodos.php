<?php

namespace App\Filament\Resources\GeneralTodoResource\Pages;

use App\Filament\Resources\GeneralTodoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeneralTodos extends ListRecords
{
    protected static string $resource = GeneralTodoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
