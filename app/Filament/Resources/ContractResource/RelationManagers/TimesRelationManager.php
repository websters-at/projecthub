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
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\IconColumn\IconColumnSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
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
                IconColumn::make('billed')
                    ->label(__('messages.time.table.billed'))
                    ->boolean()
                    ->visible(Auth::user()->hasRole('Admin'))
                    ->icon(fn(bool $state): string => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn(bool $state): string => $state ? 'success' : 'danger')
                    ->size(IconColumnSize::Medium),
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
                    BulkAction::make('markAsBilled')
                        ->visible(Auth::user()->hasRole('Admin'))
                        ->label(__('messages.time.bulk_actions.mark_as_billed.label'))
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                $record->update(['billed' => true]);
                            });
                        })
                        ->deselectRecordsAfterCompletion()
                        ->color('success')
                        ->icon('heroicon-o-currency-euro'),
                    BulkAction::make('markAsNotBilled')
                        ->label(__('messages.time.bulk_actions.mark_as_not_billed.label'))
                        ->visible(Auth::user()->hasRole('Admin'))
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                $record->update(['billed' => false]);
                            });
                        })
                        ->deselectRecordsAfterCompletion()
                        ->color('danger')
                        ->icon('heroicon-o-currency-euro'),
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
