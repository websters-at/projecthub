<?php

namespace App\Filament\Resources\ContractResource\RelationManagers;

use App\Models\ContractClassification;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TodosRelationManager extends RelationManager
{
    protected static string $relationship = 'todos';

    public static function getModelLabel(): string
    {
        return __('messages.todo.resource.name');
    }

    public static function getPluralModelLabel(): string
    {
        return __('messages.todo.resource.name_plural');
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Section::make(__('messages.todo.form.section_general'))
                ->schema([
                    TextInput::make('name')
                        ->label(__('messages.todo.form.field_name'))
                        ->required()
                        ->maxLength(255),

                    DatePicker::make('due_to')
                        ->label(__('messages.todo.form.field_due_to'))
                        ->required(),

                    MarkdownEditor::make('description')
                        ->label(__('messages.todo.form.field_description'))
                        ->nullable(),

                    Toggle::make('is_done')
                        ->label(__('messages.todo.form.field_is_done'))
                        ->default(false),

                    Select::make('priority')
                        ->label(__('messages.todo.form.field_priority_label'))
                        ->options([
                            'low' => __('messages.todo.form.field_priority.low'),
                            'mid' => __('messages.todo.form.field_priority.medium'),
                            'high' => __('messages.todo.form.field_priority.high'),
                        ])
                        ->required(),


                    Hidden::make('contract_id')
                        ->default(fn ($livewire) => $livewire->ownerRecord->id)
                        ->dehydrated()
                        ->required(),
                ])
                ->columns(1)
                ->collapsible(),

            Section::make(__('messages.todo.form.field_attachments'))
                ->schema([
                    FileUpload::make('attachments')
                        ->label(__('messages.todo.form.field_attachments'))
                        ->multiple()
                        ->disk('s3')
                        ->directory('todo_attachments')
                        ->nullable()
                        ->downloadable()
                        ->preserveFilenames()
                        ->previewable(),
                ])
                ->collapsible(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.todo.table.name'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('due_to')
                    ->label(__('messages.todo.table.due_to'))
                    ->date()
                    ->sortable(),

                ToggleColumn::make('is_done')
                    ->label(__('messages.todo.table.is_done'))
                    ->sortable(),

                TextColumn::make('priority')
                    ->sortable()
                    ->label(__('messages.todo.table.priority')),
            ])
            ->filters([
                SelectFilter::make('priority')
                    ->label(__('messages.todo.table.priority'))
                    ->options([
                        'low' => __('messages.todo.form.field_priority.low'),
                        'mid' => __('messages.todo.form.field_priority.medium'),
                        'high' => __('messages.todo.form.field_priority.high'),
                    ]),
                Filter::make('is_done')
                    ->label(__('messages.todo.table.is_done'))
                    ->query(fn (Builder $query) => $query->where('is_done', true))
                    ->toggle(),
                Filter::make('due_to')
                    ->label(__('messages.todo.table.due_to'))
                    ->form([
                        DatePicker::make('due_from')
                            ->label(__('messages.todo.table.due_to') . ' (von)'),
                        DatePicker::make('due_until')
                            ->label(__('messages.todo.table.due_to') . ' (bis)'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['due_from'],
                                fn ($q) => $q->whereDate('due_to', '>=', $data['due_from'])
                            )
                            ->when(
                                $data['due_until'],
                                fn ($q) => $q->whereDate('due_to', '<=', $data['due_until'])
                            );
                    }),
            ])
            ->headerActions([
                CreateAction::make()->visible(function (): bool {
                    $user = Auth::user();
                    $contractId = $this->ownerRecord->id;

                    return ContractClassification::where('user_id', $user->id)
                        ->where('contract_id', $contractId)
                        ->exists();
                }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
