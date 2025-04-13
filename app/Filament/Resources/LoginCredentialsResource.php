<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractLoginCredentialsResource\Pages;
use App\Filament\Resources\ContractLoginCredentialsResource\RelationManagers;
use App\Models\ContractLoginCredentials;
use App\Models\Customer;
use App\Models\LoginCredentials;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoginCredentialsResource extends Resource
{
    protected static ?string $model = LoginCredentials::class;

    protected static ?string $navigationGroup = 'Contracts';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';
    public static function getNavigationGroup(): ?string
    {
        return __('messages.login_credentials.resource.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.login_credentials.resource.name');
    }

    public static function getPluralLabel(): string
    {
        return __('messages.login_credentials.resource.name_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        Section::make(__('messages.login_credentials.form.section_general'))->schema([
                            TextInput::make('name')
                                ->required()
                                ->label(__('messages.login_credentials.form.field_name')),
                            MarkdownEditor::make('description')
                                ->label(__('messages.login_credentials.form.field_description')),
                            TextInput::make('email')
                                ->email()
                                ->label(__('messages.login_credentials.form.field_email')),
                            TextInput::make('password')
                                ->label(__('messages.login_credentials.form.field_password')),
                            FileUpload::make('attachments')
                                ->multiple()
                                ->label(__('messages.login_credentials.form.field_attachments'))
                                ->directory('login_credentials_attachments')
                                ->visibility('public')
                                ->downloadable()
                                ->disk('s3')
                                ->preserveFilenames(),
                        ])->collapsible(),
                        Section::make(__('messages.login_credentials.form.section_contracts'))
                            ->schema([
                                Select::make('contract')
                                    ->multiple()
                                    ->preload()
                                    ->relationship('contracts', 'name')
                                    ->searchable(),
                            ])
                            ->columns(1)->collapsible()
                            ->collapsed(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.login_credentials.table.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('contracts.name')
                    ->label(__('messages.login_credentials.table.contracts'))
                    ->formatStateUsing(fn ($state, $record) => $record->contracts->pluck('name')->join(', '))
                    ->sortable(),
                TextColumn::make('description')
                    ->label(__('messages.login_credentials.table.description'))
                    ->limit(50),
            ])
            ->filters([
                Filter::make('email')
                    ->label(__('messages.login_credentials.filter.email.label'))
                    ->form([
                        TextInput::make('domain')
                            ->label(__('messages.login_credentials.filter.email.placeholder')),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['domain'], function ($query, $domain) {
                            $query->where('email', 'like', '%' . $domain);
                        });
                    }),

                Filter::make('name')
                    ->label(__('messages.login_credentials.filter.name.label'))
                    ->form([
                        TextInput::make('name_contains')
                            ->label(__('messages.login_credentials.filter.name.placeholder'))
                            ->placeholder(__('messages.login_credentials.filter.name.placeholder')),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['name_contains'], function ($query, $name) {
                            $query->where('name', 'like', '%' . $name . '%');
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if ($user && $user->hasPermissionTo('View All Logins')) {
            return parent::getEloquentQuery();
        } else {
            return parent::getEloquentQuery()
                ->whereHas('contracts.classifications', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                });
        }
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContractLoginCredentials::route('/'),
            'create' => Pages\CreateContractLoginCredentials::route('/create'),
            'view' => Pages\ViewContractLoginCredentials::route('/{record}'),
            'edit' => Pages\EditContractLoginCredentials::route('/{record}/edit'),
        ];
    }
}
