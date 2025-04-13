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
                        ->required()
                        ->label(__('messages.time.form.field_date')),
                    RichEditor::make('description')
                        ->nullable()
                        ->string()
                        ->maxLength(255)
                        ->label(__('messages.time.form.field_description')),
                ])->heading(__('messages.time.form.general'))
                    ->collapsible()
                    ->collapsed(false),
                Section::make([
                    TextInput::make('total_hours_worked')
                        ->required()
                        ->label(__('messages.time.form.field_total_hours_worked')),
                    TextInput::make('total_minutes_worked')
                        ->label(__('messages.time.form.field_total_minutes_worked')),
                ])->columns(2)
                    ->collapsible()
                    ->collapsed(false)
                    ->heading(__('messages.time.form.time')),
                Section::make([
                    Toggle::make('is_special')
                        ->required()
                        ->label(__('messages.time.form.field_is_special')),
                ])->columns(1)
                    ->collapsible()
                    ->collapsed(false)
                    ->heading(__('messages.time.form.specification'))
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
                    ->date()
                    ->label(__('messages.time.table.date')),
                TextColumn::make('description')
                    ->markdown()
                    ->limit(30)
                    ->label(__('messages.time.table.description')),
                TextColumn::make('total_hours_worked')
                    ->label(__('messages.time.table.total_hours')),
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
                    ->label(__('messages.time.table.is_special')),
            ])->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                $contractId = $this->ownerRecord->id;
                if ($user->hasPermissionTo('View All Times')) {
                    return $query;
                } else {
                    $query->whereHas('contractClassification', function (Builder $query) use ($user, $contractId) {
                        $query->where('user_id', $user->id)
                            ->where('contract_id', $contractId);
                    });
                }
            })
            ->headerActions([
                CreateAction::make()->mutateFormDataUsing(function (array $data): array {
                    $user = Auth::user();
                    $contractId = $this->ownerRecord->id;

                    // Fetch the contract classification
                    $contractClassification = ContractClassification::where('user_id', $user->id)
                        ->where('contract_id', $contractId)
                        ->first();

                    if ($contractClassification) {
                        // Ensure the contract_classification_id is included in the data
                        $data['contract_classification_id'] = $contractClassification->id;
                    } else {
                        // If there's no contract classification, handle the case (e.g., throw an error or set a default value)
                        // Optionally, you could set a default value or handle it in another way.
                    }

                    return $data;
                })->visible(function (): bool {
                    $user = Auth::user();
                    $contractId = $this->ownerRecord->id;

                    return ContractClassification::where('user_id', $user->id)
                        ->where('contract_id', $contractId)
                        ->exists();
                }),
            ])
            ->actions([
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
