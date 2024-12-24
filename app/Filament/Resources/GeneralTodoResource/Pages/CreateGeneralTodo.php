<?php

namespace App\Filament\Resources\GeneralTodoResource\Pages;

use App\Filament\Resources\GeneralTodoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGeneralTodo extends CreateRecord
{
    protected static string $resource = GeneralTodoResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
