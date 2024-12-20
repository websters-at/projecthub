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

    public function form(Form $form): Form
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
                    DatePicker::make('due_to')
                        ->required()
                ])->collapsible()
                    ->collapsed(false),
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
                ])->columns(2)->collapsible()
                    ->collapsed(false),

                Section::make('Contract Attachment')->schema([
                    FileUpload::make('attachments')
                        ->columns(1)
                        ->multiple()
                        ->nullable()
                        ->directory('contracts_attachments')
                        ->downloadable()
                        ->preserveFilenames()
                        ->previewable()
                ])->collapsible()
                    ->collapsed(false)
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
                    ->limit(30),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('description')
                    ->limit(30)
                    ->searchable()
                    ->markdown(),
                TextColumn::make('city')
                    ->limit(30)
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
