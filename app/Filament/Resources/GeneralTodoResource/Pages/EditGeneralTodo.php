<?php

namespace App\Filament\Resources\GeneralTodoResource\Pages;

use App\Filament\Resources\GeneralTodoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGeneralTodo extends EditRecord
{
    protected static string $resource = GeneralTodoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
