<?php

namespace App\Filament\Resources\CallNotesResource\Pages;

use App\Filament\Resources\CallNotesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCallNotes extends ViewRecord
{
    protected static string $resource = CallNotesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
