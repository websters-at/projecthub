<?php

namespace App\Filament\Resources\NoteResource\Pages;

use App\Filament\Resources\ContractNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNote extends CreateRecord
{
    protected static string $resource = ContractNoteResource::class;
}
