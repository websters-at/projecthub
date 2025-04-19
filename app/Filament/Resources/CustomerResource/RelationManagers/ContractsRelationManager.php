<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Models\ContractClassification;
use App\Models\Customer;
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
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ContractsRelationManager extends RelationManager
{
    protected static string $relationship = 'contracts';

    public static function getModelLabel(): string
    {
        return __('messages.contract.resource.name');
    }
    public static function getPluralModelLabel(): string
    {
        return __('messages.contract.resource.name_plural');
    }

    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('messages.contract.form.section_general'))->schema([
                    TextInput::make('name')
                        ->required()
                        ->label(__('messages.contract.form.field_name')),
                    RichEditor::make('description')
                        ->nullable()
                        ->string()
                        ->label(__('messages.contract.form.field_description')),
                    Select::make('priority')
                        ->label(__('messages.contract.form.field_priority'))
                        ->options([
                            'low' => 'Low',
                            'mid' => 'Medium',
                            'high' => 'High',
                        ])
                        ->searchable()
                        ->required(),
                    DatePicker::make('due_to')
                        ->required()
                        ->label(__('messages.contract.form.field_due_to')),
                    Toggle::make('is_finished')->label(__('messages.contract.form.field_is_finished'))->nullable(),
                ])->collapsible()
                    ->collapsed(false),

                Section::make(__('messages.contract.form.section_location'))->schema([
                    TextInput::make('country')
                        ->nullable()
                        ->label(__('messages.contract.form.field_country')),
                    TextInput::make('state')
                        ->nullable()
                        ->label(__('messages.contract.form.field_state')),
                    TextInput::make('city')
                        ->nullable()
                        ->label(__('messages.contract.form.field_city')),
                    TextInput::make('zip_code')
                        ->nullable()
                        ->label(__('messages.contract.form.field_zip_code')),
                    TextInput::make('address')
                        ->nullable()
                        ->label(__('messages.contract.form.field_address')),
                ])->columns(2)->collapsible()
                    ->collapsed(false),

                Section::make(__('messages.contract.form.section_attachments'))->schema([
                    FileUpload::make('attachments')
                        ->columns(1)
                        ->multiple()
                        ->nullable()
                        ->directory('contracts_attachments')
                        ->downloadable()
                        ->preserveFilenames()
                        ->previewable()
                        ->label(__('messages.contract.form.field_attachments')),
                ])->collapsible()
                    ->collapsed(false),

                Section::make(__('messages.contract.form.section_employees'))->schema([
                    Select::make('users')
                        ->multiple()
                        ->preload()
                        ->relationship('users', 'email')
                        ->searchable()
                        ->label(__('messages.contract.form.field_users')),
                ])
                    ->columns(1)->collapsible()
                    ->collapsed(false),
            ]);
    }


    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('due_to')
                    ->date()
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->label(__('messages.contract.table.due_to')),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->label(__('messages.contract.table.name')),
                TextColumn::make('description')
                    ->limit(30)
                    ->searchable()
                    ->markdown()
                    ->label(__('messages.contract.table.description')),
                TextColumn::make('city')
                    ->limit(30)
                    ->label(__('messages.contract.form.field_city')),
            ])
            ->filters([

            ])
            ->headerActions([
                CreateAction::make()->mutateFormDataUsing(function (array $data): array {
                     $data['customer_id'] = $this->ownerRecord->id;
                    return $data;
                })
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
