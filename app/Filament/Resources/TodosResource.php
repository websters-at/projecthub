<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TodosResource\Pages;
use App\Filament\Resources\TodosResource\RelationManagers;
use App\Models\Todo;
use App\Models\Todos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TodosResource extends Resource
{
    protected static ?string $model = Todo::class;

    protected static ?string $navigationIcon = 'fas-folder';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTodos::route('/'),
            'create' => Pages\CreateTodos::route('/create'),
            'view' => Pages\ViewTodos::route('/{record}'),
            'edit' => Pages\EditTodos::route('/{record}/edit'),
        ];
    }
}
