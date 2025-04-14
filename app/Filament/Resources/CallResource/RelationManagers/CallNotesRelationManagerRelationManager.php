<?php

namespace App\Filament\Resources\CallResource\RelationManagers;

use App\Models\Contract;
use App\Models\ContractClassification;

use Filament\Actions\CreateAction;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CallNotesRelationManagerRelationManager extends RelationManager
{
    protected static string $relationship = 'call_notes';
    public static function getModelLabel(): string
    {
        return __('messages.call_note.resource.name');
    }
    public static function getPluralModelLabel(): string
    {
        return __('messages.call_note.resource.name_plural');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('messages.call.form.field_name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('messages.call.form.field_name')),

                        MarkdownEditor::make('description')
                            ->label(__('messages.call.form.field_description'))
                            ->placeholder(__('messages.call.form.field_description')),
                    ])
                    ->heading(__('messages.call.form.section_general')),
            ]);
    }
    public function table(Table $table): Table
    {
        return $table
            ->columns([
             TextColumn::make('name')
                    ->label(__('messages.call_note.table.name'))
                    ->sortable()
                    ->searchable(),

               TextColumn::make('description')
                    ->label(__('messages.call_note.table.description'))
                    ->limit(50)
                    ->sortable()
                    ->searchable()
                    ->markdown(),

            ])

            ->actions([
               ViewAction::make(),
               EditAction::make(),
               DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
    public function isReadOnly(): bool
    {
        return false;
    }




}
