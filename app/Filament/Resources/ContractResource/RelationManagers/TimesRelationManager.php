<?php

namespace App\Filament\Resources\ContractResource\RelationManagers;

use App\Models\ContractClassification;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\IconColumn\IconColumnSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TimesRelationManager extends RelationManager
{
    protected static string $relationship = 'times';

    public function form(Form $form): Form
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
                    TextInput::make('total_hours_worked')
                        ->required(),
                    TextInput::make('total_minutes_worked')
                ])->columns(2)
                    ->collapsible()
                    ->collapsed(false)
                    ->heading('Time'),
                Section::make([
                    Toggle::make('is_special')
                        ->required()
                ])->columns(1)
                    ->collapsible()
                    ->collapsed(false)
                    ->heading('Specification')
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('date')
            ->columns([
                TextColumn::make('date')
                    ->date(),
                TextColumn::make('description')->markdown()->limit(30)  ,
                TextColumn::make('start_time')
                    ->time(),
                TextColumn::make('total_hours_worked'),
                IconColumn::make('is_special')
                    ->icon(fn (bool $state): string => match ($state) {
                        false => 'fas-x',
                        true => 'fas-check',
                    })
                    ->color(fn (bool $state): string => match ($state) {
                        false => 'danger',
                        true => 'success',
                    })
                    ->size(IconColumnSize::Medium)
            ])->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                $contractId = $this->ownerRecord->id;
                if($user->hasPermissionTo('View All Times')){
                    return $query;
                }else{
                    $query->whereHas('contractClassification', function (Builder $query) use ($user, $contractId) {
                        $query->where('user_id', $user->id)
                            ->where('contract_id', $contractId);
                    });
                }
            })
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->mutateFormDataUsing(function (array $data): array {
                    $user = Auth::user();
                    $contractId = $this->ownerRecord->id;

                    $contractClassification = ContractClassification::where('user_id', $user->id)
                        ->where('contract_id', $contractId)
                        ->first();

                    if ($contractClassification) {
                        $data['contract_classification_id'] = $contractClassification->id;
                    }
                    return $data;
                })->visible(function(): bool{
                    $user = Auth::user();
                    $contractId = $this->ownerRecord->id;

                    return ContractClassification::where('user_id', $user->id)
                        ->where('contract_id', $contractId)
                        ->exists();
                }),
            ])
            ->actions([
                CreateAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if ($user && $user->hasPermissionTo('View All Times')) {
            return self::getEloquentQuery();
        } else {
            return self::getEloquentQuery()
                ->whereHas('classifications', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                });
        }
    }
}
