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
    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                ->schema([
                    Section::make('General')->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Name'),
                        MarkdownEditor::make('description')
                            ->label('Description'),
                        TextInput::make('email')
                            ->email()
                            ->label('Email'),
                        TextInput::make('password')
                            ->label('Password'),
                        FileUpload::make('attachments')
                            ->multiple()
                            ->label('Attachments')
                            ->directory('login_credentials_attachments')
                            ->visibility('public')
                            ->preserveFilenames(),
                    ])->collapsible(),
                    Section::make('Contracts')
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
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('contracts.name')
                    ->label('Contracts')
                    ->formatStateUsing(fn ($state, $record) => $record->contracts->pluck('name')->join(', '))
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50),
            ])
            ->filters([
                Filter::make('email')
                    ->label('Email Domain')
                    ->form([
                        TextInput::make('domain')
                            ->label('Domain')
                            ->placeholder('Enter domain (e.g., example.com)')
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['domain'], function ($query, $domain) {
                            $query->where('email', 'like', '%' . $domain);
                        });
                    }),

                Filter::make('name')
                    ->label('Name')
                    ->form([
                        TextInput::make('name_contains')
                            ->label('Contains')
                            ->placeholder('Search by name'),
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
