<?php

namespace App\Filament\Resources\NoteResource\Pages;

use App\Filament\Resources\ContractNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotes extends ListRecords
{
    protected static string $resource = ContractNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
