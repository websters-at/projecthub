<?php

namespace App\Filament\Resources\ContractResource\RelationManagers;

use App\Models\ContractClassification;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class BillsRelationManager extends RelationManager
{
    protected static string $relationship = 'bills';

    public static function getModelLabel(): string
    {
        return __('messages.bill.resource.name');
    }
    public static function getPluralModelLabel(): string
    {
        return __('messages.bill.resource.name_plural');
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Section::make(__('messages.bill.form.section_general'))->schema([
                TextInput::make('name')
                    ->label(__('messages.bill.form.field_name'))
                    ->required()
                    ->maxLength(255),

                Toggle::make('is_flat_rate')
                    ->label(__('messages.bill.form.field_is_flat_rate'))
                    ->helperText(__('messages.bill.form.field_is_flat_rate_helper'))
                    ->default(false)
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, $set, $state) {
                        if (! $state) {
                            $set('flat_rate_amount', null);
                        }
                    }),

                TextInput::make('hourly_rate')
                    ->label(__('messages.bill.form.field_hourly_rate'))
                    ->numeric()
                    ->prefix('€')
                    ->disabled(fn (Get $get): bool => $get('is_flat_rate'))
                    ->requiredIf('is_flat_rate', false),

                TextInput::make('flat_rate_amount')
                    ->label(__('messages.bill.form.field_flat_rate_amount'))
                    ->numeric()
                    ->prefix('€')
                    ->disabled(fn(Get $get): bool => !$get('is_flat_rate'))
                    ->requiredIf('is_flat_rate', true),

                TextInput::make('flat_rate_hours')
                    ->label(__('messages.bill.form.field_flat_rate_hours'))
                    ->numeric()
                    ->prefix('h')
                    ->disabled(fn(Get $get): bool => !$get('is_flat_rate'))
                    ->requiredIf('is_flat_rate', true),

                RichEditor::make('description')
                    ->label(__('messages.bill.form.field_description'))
                    ->nullable(),

                DatePicker::make('due_to')
                    ->label(__('messages.bill.form.field_due_to')),

                DatePicker::make('created_on')
                    ->label(__('messages.bill.form.field_created_on'))
                    ->nullable()
                    ->default(now()),

                Toggle::make('is_payed')
                    ->label(__('messages.bill.form.field_is_payed'))
                    ->nullable(),
                Hidden::make('contract_classification_id')
                    ->default(fn ($livewire) => ContractClassification::query()
                        ->where('contract_id', $livewire->ownerRecord->id)
                        ->where('user_id', Auth::id())
                        ->value('id')
                    )
                    ->dehydrated()

                    ->required()
            ])->columns(2)->collapsible(),

            Section::make(__('messages.bill.form.section_attachments'))->schema([
                FileUpload::make('attachments')
                    ->label(__('messages.bill.form.field_attachments'))
                    ->multiple()
                    ->disk('s3')
                    ->nullable()
                    ->directory('bills_attachments')
                    ->downloadable()
                    ->preserveFilenames()
                    ->previewable()
            ])->collapsible(),

        ]);
    }
    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.bill.form.field_name'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('hourly_rate')
                    ->label(__('messages.bill.table.calculated_total'))
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->is_flat_rate) {
                            return '/';
                        }

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

                TextColumn::make('flat_rate_amount')
                    ->label(__('messages.bill.form.field_flat_rate_amount'))
                    ->formatStateUsing(fn($state, $record) => $record->is_flat_rate
                        ? ($state ? $state . ' €' : '—') : '/'),

                BooleanColumn::make('is_payed')
                    ->label(__('messages.bill.table.is_payed')),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

}
