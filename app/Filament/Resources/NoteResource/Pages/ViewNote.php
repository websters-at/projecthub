<?php

namespace App\Filament\Resources\NoteResource\Pages;

use App\Filament\Resources\ContractNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNote extends ViewRecord
{
    protected static string $resource = ContractNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
