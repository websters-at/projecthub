<?php

namespace App\Filament\Resources\TodosResource\Pages;

use App\Filament\Resources\TodosResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTodos extends CreateRecord
{
    protected static string $resource = TodosResource::class;
}
