<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use App\Models\Role;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;


    protected static ?string $navigationIcon = 'heroicon-o-hand-raised';

    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('messages.role.resource.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.role.resource.name');
    }

    public static function getPluralLabel(): string
    {
        return __('messages.role.resource.name_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('messages.role.form.section_general'))->schema([
                    Forms\Components\TextInput::make('name')
                        ->minLength(2)
                        ->maxLength(255)
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->label(__('messages.role.form.field_name')),
                    Select::make('permissions')
                        ->multiple()
                        ->relationship('permissions', 'name')
                        ->searchable()
                        ->preload()
                        ->label(__('messages.role.form.field_permissions')),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label(__('messages.role.table.name')),
                TextColumn::make('created_at')
                    ->sortable()
                    ->searchable()
                    ->label(__('messages.role.table.created_at')),
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
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
