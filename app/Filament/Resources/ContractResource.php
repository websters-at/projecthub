<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractResource\Pages;
use App\Filament\Resources\ContractResource\RelationManagers;
use App\Models\Contract;
use App\Models\ContractClassification;
use App\Models\Customer;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationIcon = 'fas-list-check';
    protected static ?string $navigationGroup = 'Time Tracking';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Section::make('General')->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        RichEditor::make('description')
                           ->nullable()
                            ->string()
                           ->maxLength(255),
                        DatePicker::make('due_to')
                            ->nullable()
                    ])->collapsible()
                        ->collapsed(false),
                Section::make('Customer')->schema([
                    Select::make('customer_id')
                        ->options(Customer::all()
                            ->pluck('company_name','id'))
                        ->searchable()
                    ->label('Customer')
                    ->required()
                ])->columns(1)->collapsible()
                    ->collapsed(false),

                Section::make('Employees')
                    ->schema([
                        Select::make('users')
                            ->multiple()
                            ->preload()
                            ->relationship('users', 'email')
                            ->searchable(),
                    ])
                    ->columns(1)->collapsible()
                    ->collapsed(false),

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
                    ])->columns(2)->collapsible()
                        ->collapsed(false),

                Section::make('Contract Picture')->schema([
                    FileUpload::make('contract_image')
                    ->columns(1)
                    ->multiple()
                    ->nullable()
                    ->directory('contract_images')
                    ->storeFileNamesIn('original_filename')
                ])->collapsible()
                ->collapsed(false)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('due_to')
                    ->date()
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('description')
                    ->limit(30)
                    ->searchable()
                    ->markdown(),
                TextColumn::make('city')
                    ->limit(30),
                TextColumn::make('zip_code')
                    ->limit(30)
                    ->searchable(),
                TextColumn::make('address')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('customer')
                    ->relationship('customer', 'company_name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('users')
                    ->relationship('users', 'email')
                    ->multiple()
                    ->searchable()
                    ->visible(function(): bool{
                        return Auth::user()->hasPermissionTo('View Special Contracts Filters');
                })->preload()
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
            RelationManagers\TimesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'view' => Pages\ViewContract::route('/{record}'),
            'edit' => Pages\EditContract::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if ($user && $user->hasPermissionTo('View All Contracts')) {
            return parent::getEloquentQuery();
        } else {
            return parent::getEloquentQuery()
                ->whereHas('classifications', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                });
        }
    }
}
