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
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    public static function getModelLabel(): string
    {
        return __('messages.credentials.resource.name');
    }

    public static function getPluralModelLabel(): string
    {
        return __('messages.credentials.resource.name_plural');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('messages.credentials.resource.group');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make(__('messages.credentials.form.section_general'))->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label(__('messages.credentials.form.field_name')),

                TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->nullable()
                    ->label(__('messages.credentials.form.field_email')),

                TextInput::make('password')
                    ->password()
                    ->nullable()
                    ->maxLength(255)
                    ->label(__('messages.credentials.form.field_password')),

                MarkdownEditor::make('description')
                    ->nullable()
                    ->label(__('messages.credentials.form.field_description')),

                FileUpload::make('attachments')
                    ->nullable()
                    ->multiple()
                    ->downloadable()
                    ->disk('s3')
                    ->directory('general_software_credentials_attachments')
                    ->preserveFilenames()
                    ->label(__('messages.credentials.form.field_attachments')),
            ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.credentials.table.name'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label(__('messages.credentials.table.email'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('description')
                    ->label(__('messages.credentials.table.description'))
                    ->limit(50),

                TextColumn::make('created_at')
                    ->label(__('messages.credentials.table.created_at'))
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\Filter::make('email')
                    ->label(__('messages.credentials.filters.email'))
                    ->form([
                        TextInput::make('domain')
                            ->label('Domain')
                            ->placeholder('example.com'),
                    ])
                    ->query(fn (Builder $query, array $data) =>
                    $query->when($data['domain'], fn ($query, $domain) =>
                    $query->where('email', 'like', '%' . $domain)
                    )
                    ),

                Tables\Filters\Filter::make('name')
                    ->label(__('messages.credentials.filters.name'))
                    ->form([
                        TextInput::make('name_contains')
                            ->label('Contains')
                            ->placeholder(__('messages.credentials.filters.name')),
                    ])
                    ->query(fn (Builder $query, array $data) =>
                    $query->when($data['name_contains'], fn ($query, $name) =>
                    $query->where('name', 'like', '%' . $name . '%')
                    )
                    ),
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
