<?php

namespace App\Filament\Resources\ContractResource\RelationManagers;

use App\Models\ContractClassification;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    public static function getModelLabel(): string
    {
        return __('messages.contract_note.resource.name');
    }

    public static function getPluralModelLabel(): string
    {
        return __('messages.contract_note.resource.name_plural');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('messages.contract_note.form.section_general'))->schema([
                    TextInput::make('name')
                        ->label(__('messages.contract_note.form.field_name'))
                        ->required()
                        ->maxLength(255)
                        ->placeholder(__('messages.contract_note.form.field_name_placeholder')),

                    MarkdownEditor::make('description')
                        ->label(__('messages.contract_note.form.field_description'))
                        ->placeholder(__('messages.contract_note.form.field_description_placeholder')),

                    DateTimePicker::make('date')
                        ->label(__('messages.contract_note.form.field_date'))
                        ->default(now())
                        ->required(),
                ]),
                Section::make(__('messages.contract_note.form.section_contract'))->schema([
                    FileUpload::make('attachments')
                        ->label(__('messages.contract_note.form.field_attachments'))
                        ->multiple()
                        ->directory('contracts_notes')
                        ->preserveFilenames()
                        ->downloadable()
                        ->acceptedFileTypes(['application/pdf', 'image/*', 'text/plain'])
                        ->maxSize(5120)
                        ->hint(__('messages.contract_note.form.field_attachments_hint')),
                ]),
            ]);
    }
    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.contract_note.table.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date')
                    ->label(__('messages.contract_note.table.date'))
                    ->sortable(),
                TextColumn::make('contractClassification.contract.name')
                    ->label(__('messages.contract_note.table.contract'))
                    ->sortable()
                    ->limit(10)
                    ->searchable(),
            ])
            ->filters([
                // Filters can be added here if needed
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
                })
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
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        if ($user && $user->hasPermissionTo('View All Notes')) {
            return self::getEloquentQuery();
        } else {
            return self::getEloquentQuery()
                ->whereHas('classifications', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                });
        }
    }
}
