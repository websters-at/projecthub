<?php

namespace App\Filament\Resources\TodosResource\Pages;

use App\Filament\Resources\TodosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTodos extends EditRecord
{
    protected static string $resource = TodosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
