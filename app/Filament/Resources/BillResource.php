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
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class BillResource extends Resource
{
    protected static ?string $model = Bill::class;

    protected static ?string $navigationGroup = 'Time Tracking';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    FileUpload::make('contract_image')
                        ->columns(1)
                        ->multiple()
                        ->nullable()
                        ->directory('contract_images')
                        ->previewable()
                        ->storeFileNamesIn('original_filename')
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
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Bill Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(30)
                    ->markdown()
                    ->sortable(),
                TextColumn
                    ::make('created_on')
                    ->label('Created On')
                    ->sortable(),
                TextColumn::make('due_to')
                    ->label('Due Date')
                    ->sortable(),

                TextColumn::make('contractClassification.hourly_rate')
                    ->label('Hourly Rate')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state, 2) . ' $'),
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
