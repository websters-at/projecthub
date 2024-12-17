<?php

namespace App\Filament\Resources\CallNotesResource\Pages;

use App\Filament\Resources\CallNotesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCallNotes extends ListRecords
{
    protected static string $resource = CallNotesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
