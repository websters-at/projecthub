<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'fas-handshake';
    protected static ?string $navigationGroup = 'Configuration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    TextInput::make('company_name')
                        ->maxLength(255)
                        ->required()
                ]),
                Section::make('Address')->schema([
                    TextInput::make('country')
                        ->nullable()
                        ->maxLength(255),
                    TextInput::make('state')
                        ->nullable()
                        ->maxLength(255),
                    TextInput::make('city')
                        ->nullable()
                        ->maxLength(255),
                    TextInput::make('zip_code')
                        ->nullable()
                        ->maxLength(255),
                    TextInput::make('address')
                        ->nullable()
                        ->maxLength(255)
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_name')
                    ->limit(30)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('country')
                    ->limit(30)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('state')
                    ->limit(30)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city')
                    ->limit(30)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('zip_code')
                    ->limit(30)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('address')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make(),

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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
