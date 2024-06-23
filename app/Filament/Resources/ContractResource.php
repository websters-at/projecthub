<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractResource\Pages;
use App\Filament\Resources\ContractResource\RelationManagers;
use App\Models\Contract;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationIcon = 'fas-list-check';
    protected static ?string $navigationGroup = 'Time Tracking';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Section::make([
                        TextInput::make('name')
                            ->nullable()
                            ->maxLength(255),
                        RichEditor::make('description')
                           ->nullable()
                            ->string()
                           ->maxLength(255),
                        DatePicker::make('due_to')
                            ->nullable()
                    ]),
                Section::make('Customer')->schema([
                    Select::make('customer_id')
                        ->options(Customer::all()
                            ->pluck('company_name','id'))
                        ->searchable()
                    ->label('Customer')
                    ->required()
                ])->columns(1),

                Section::make('Employees')
                    ->schema([
                        Select::make('users')
                            ->multiple()
                            ->preload()
                            ->relationship('users', 'email')
                            ->searchable(),
                    ])
                    ->columns(1),

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

                Section::make('Contract Picture')->schema([
                    FileUpload::make('contract_image')
                    ->columns(1)
                    ->multiple()
                    ->nullable()
                    ->directory('contract_images')
                    ->storeFileNamesIn('original_filename')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('due_to')
                    ->date()
                    ->limit(30),
                TextColumn::make('name')
                    ->limit(30),
                TextColumn::make('description')
                    ->limit(30)
                    ->markdown(),
                TextColumn::make('city')
                    ->limit(30),
                TextColumn::make('zip_code')
                    ->limit(30),
                TextColumn::make('address'),
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
            'index' => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'view' => Pages\ViewContract::route('/{record}'),
            'edit' => Pages\EditContract::route('/{record}/edit'),
        ];
    }


  /*  public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if ($user && $user->hasRole('admin')) {
            return parent::getEloquentQuery();
        } else {
            return parent::getEloquentQuery()
                ->join('contract_classifications', 'contracts.id', '=', 'contract_classifications.contract_id')
                ->where('contract_classifications.user_id', $user->id)
                ->select('contracts.*');
        }
    }*/
}
