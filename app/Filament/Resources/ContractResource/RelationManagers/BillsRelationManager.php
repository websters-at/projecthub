<?php

namespace App\Filament\Resources\ContractResource\RelationManagers;

use App\Models\ContractClassification;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class BillsRelationManager extends RelationManager
{
    protected static string $relationship = 'bills';

    public function form(Form $form): Form
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
                        ->nullable(),
                    Toggle::make('is_payed')
                        ->default(false)
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
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
                Tables\Columns\ToggleColumn::make('is_payed')
                ->label('Payed')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
