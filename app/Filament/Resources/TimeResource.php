<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeResource\Pages;
use App\Filament\Resources\TimeResource\RelationManagers;
use App\Models\Contract;
use App\Models\ContractClassification;
use App\Models\Time;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TimeResource extends Resource
{
    protected static ?string $model = Time::class;
    protected static ?string $navigationGroup = 'Time Tracking';
    protected static ?string $navigationIcon = 'far-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    DatePicker::make('date')
                        ->required(),
                    RichEditor::make('description')
                        ->nullable()
                        ->string()
                        ->maxLength(255),
                ])->heading('General')
                    ->collapsible()
                    ->collapsed(false),
                Section::make([
                    TimePicker::make('start_time')
                        ->required(),
                    TimePicker::make('end_time')
                        ->required(),
                    TextInput::make('total_hours_worked')
                        ->required(),
                    TextInput::make('total_minutes_worked')
                ])->columns(2)
                    ->collapsible()
                    ->collapsed(false)
                    ->heading('Time'),
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
                Section::make([
                    Toggle::make('is_special')
                        ->required()
                ])->columns(1)
                    ->collapsible()
                    ->collapsed(false)
                    ->heading('Specification')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListTimes::route('/'),
            'create' => Pages\CreateTime::route('/create'),
            'view' => Pages\ViewTime::route('/{record}'),
            'edit' => Pages\EditTime::route('/{record}/edit'),
        ];
    }
}
