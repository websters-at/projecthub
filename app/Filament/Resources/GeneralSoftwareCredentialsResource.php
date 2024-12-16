<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeneralSoftwareCredentialsResource\Pages;
use App\Filament\Resources\GeneralSoftwareCredentialsResource\RelationManagers;
use App\Models\GeneralSoftwareCredentials;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GeneralSoftwareCredentialsResource extends Resource
{
    protected static ?string $model = GeneralSoftwareCredentials::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            'index' => Pages\ListGeneralSoftwareCredentials::route('/'),
            'create' => Pages\CreateGeneralSoftwareCredentials::route('/create'),
            'view' => Pages\ViewGeneralSoftwareCredentials::route('/{record}'),
            'edit' => Pages\EditGeneralSoftwareCredentials::route('/{record}/edit'),
        ];
    }
}
