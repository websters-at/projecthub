<?php

namespace App\Filament\Resources\CallNotesResource\Pages;

use App\Filament\Resources\CallNotesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCallNotes extends EditRecord
{
    protected static string $resource = CallNotesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
