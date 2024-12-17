<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillResource\Pages;
use App\Filament\Resources\BillResource\Pages\CreateBill;
use App\Filament\Resources\BillResource\Pages\EditBill;
use App\Filament\Resources\BillResource\Pages\ListBills;
use App\Filament\Resources\BillResource\Pages\ViewBill;
use App\Filament\Resources\BillResource\RelationManagers;
use App\Models\Bill;
use App\Models\ContractClassification;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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
use Illuminate\Support\Facades\Auth;

class BillResource extends Resource
{
    protected static ?string $model = Bill::class;

    protected static ?string $navigationGroup = 'Contracts';
    protected static ?int $navigationSort= 5;


    protected static ?string $navigationIcon = 'fas-money-bill';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General')->schema(components: [
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('hourly_rate')
                        ->required()
                        ->numeric()
                        ->prefix("€"),
                    RichEditor::make('description')
                        ->nullable()
                        ->string()
                        ->maxLength(255),
                    DatePicker::make('due_to'),
                    DatePicker::make('created_on')
                        ->nullable()
                ])->collapsible()
                    ->collapsed(false),
                Section::make([
                    Select::make('contract_classification_id')
                        ->label('Contract')
                        ->options(function () {
                            $user = Auth::user();
                            return ContractClassification::where('user_id', $user->id)
                                ->with('contract')
                                ->get()
                                ->pluck('contract.name', 'id');
                        })
                        ->preload()
                        ->searchable()
                        ->required()
                ])->columns(1)
                    ->collapsible()
                    ->collapsed(false)
                    ->heading('Contract'),
                Section::make('Contract Picture')->schema([
                    FileUpload::make('attachments')
                        ->columns(1)
                        ->multiple()
                        ->nullable()
                        ->directory('bills_attachments')
                        ->downloadable()
                        ->preserveFilenames()
                        ->previewable()
                ])->collapsible()
                    ->collapsed(false)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contractClassification.user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('contractClassification.contract.name')
                    ->label('Contract')
                    ->sortable()
                    ->limit(10)
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Bill Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(10)
                    ->markdown()
                    ->sortable(),
                TextColumn::make('hourly_rate')
                    ->label('€')
                    ->formatStateUsing(function ($state, $record) {
                        $roundedTotalHours = $record
                            ->contractClassification
                            ->times
                            ->map(function ($time) {
                                $hours = $time->total_hours_worked;
                                $minutes = $time->total_minutes_worked;
                                $fractionalHours = $minutes / 60;
                                return $fractionalHours >= 0.5 ? ceil($hours + $fractionalHours) : $hours + $fractionalHours;
                            })
                            ->sum();

                        $calculatedPrice = $state * $roundedTotalHours;
                        return $calculatedPrice . ' €';
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
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
            'index' => ListBills::route('/'),
            'create' => CreateBill::route('/create'),
            'view' => ViewBill::route('/{record}'),
            'edit' => EditBill::route('/{record}/edit')
        ];
    }
}
