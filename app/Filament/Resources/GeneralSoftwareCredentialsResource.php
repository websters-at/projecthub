<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeneralSoftwareCredentialsResource\Pages;
use App\Filament\Resources\GeneralSoftwareCredentialsResource\RelationManagers;
use App\Models\GeneralSoftwareCredentials;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GeneralSoftwareCredentialsResource extends Resource
{
    protected static ?string $model = GeneralSoftwareCredentials::class;

    protected static ?string $navigationGroup = 'General';
    protected static ?int $navigationSort = 15;

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Information')->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->label('Credential Name'),
                    TextInput::make('email')
                        ->email()
                        ->maxLength(255)
                        ->nullable()
                        ->label('Email'),
                    TextInput::make('password')
                        ->password()
                        ->nullable()
                        ->maxLength(255)
                        ->label('Password'),
                    MarkdownEditor::make('description')
                        ->nullable()
                        ->label('Description'),
                    FileUpload::make('attachments')
                        ->nullable()
                        ->multiple()
                        ->label('Attachments')
                        ->directory('general_software_credentials_attachments')
                        ->preserveFilenames(),
                ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\Filter::make('email')
                    ->label('Email Domain')
                    ->form([
                        TextInput::make('domain')
                            ->label('Domain')
                            ->placeholder('example.com'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['domain'], fn ($query, $domain) => $query->where('email', 'like', '%' . $domain));
                    }),
                Tables\Filters\Filter::make('name')
                    ->label('Name Contains')
                    ->form([
                        TextInput::make('name_contains')
                            ->label('Contains')
                            ->placeholder('Search by name'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['name_contains'], fn ($query, $name) => $query->where('name', 'like', '%' . $name . '%'));
                    }),
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
