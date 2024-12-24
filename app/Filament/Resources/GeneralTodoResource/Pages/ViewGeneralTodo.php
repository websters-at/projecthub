<?php

namespace App\Filament\Resources\GeneralTodoResource\Pages;

use App\Filament\Resources\GeneralTodoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGeneralTodo extends ViewRecord
{
    protected static string $resource = GeneralTodoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
