<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeneralTodoResource\Pages;
use App\Models\ContractClassification;
use App\Models\GeneralTodo;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class GeneralTodoResource extends Resource
{
    protected static ?string $model = GeneralTodo::class;
    protected static ?string $navigationGroup = 'General';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    public static function getModelLabel(): string
    {
        return __('messages.general_todo.resource.name');
    }

    public static function getPluralModelLabel(): string
    {
        return __('messages.general_todo.resource.name_plural');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('messages.general_todo.resource.group');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('messages.general_todo.form.general'))->schema([
                    TextInput::make('name')
                        ->label(__('messages.general_todo.form.name'))
                        ->required()
                        ->maxLength(255),

                    DateTimePicker::make('due_to')
                        ->label(__('messages.general_todo.form.due_to'))
                        ->required(),

                    MarkdownEditor::make('description')
                        ->label(__('messages.general_todo.form.description')),

                    Toggle::make('is_done')
                        ->label(__('messages.general_todo.form.is_done'))
                        ->default(false),

                    Select::make('priority')
                        ->label(__('messages.general_todo.form.priority'))
                        ->options([
                            'low' => __('messages.general_todo.form.priority_options.low'),
                            'mid' => __('messages.general_todo.form.priority_options.mid'),
                            'high' => __('messages.general_todo.form.priority_options.high'),
                        ])
                        ->default('medium')
                        ->required(),

                    FileUpload::make('attachments')
                        ->label(__('messages.general_todo.form.attachments'))
                        ->multiple()
                        ->disk('s3')
                        ->acceptedFileTypes(['image/*', 'application/pdf', 'text/plain'])
                        ->directory('todos_attachments'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.general_todo.table.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label(__('messages.general_todo.table.user'))
                    ->sortable(),

                TextColumn::make('due_to')
                    ->label(__('messages.general_todo.table.due_to'))
                    ->time()
                    ->sortable(),

                TextColumn::make('priority')
                    ->label(__('messages.general_todo.table.priority'))
                    ->sortable(),

                ToggleColumn::make('is_done')
                    ->label(__('messages.general_todo.table.is_done'))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('priority')
                    ->label(__('messages.general_todo.filters.priority'))
                    ->options([
                        'low' => __('messages.general_todo.form.priority_options.low'),
                        'mid' => __('messages.general_todo.form.priority_options.mid'),
                        'high' => __('messages.general_todo.form.priority_options.high'),
                    ])
                    ->searchable(),

                Filter::make('is_done')
                    ->label(__('messages.general_todo.filters.is_done'))
                    ->query(fn (Builder $query) => $query->where('is_done', true))
                    ->toggle(),

                Filter::make('due_to')
                    ->label(__('messages.general_todo.filters.due_to'))
                    ->form([
                        DateTimePicker::make('due_from')->label(__('messages.general_todo.filters.due_from')),
                        DateTimePicker::make('due_until')->label(__('messages.general_todo.filters.due_until')),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['due_from'], fn ($query, $date) => $query->where('due_to', '>=', $date))
                            ->when($data['due_until'], fn ($query, $date) => $query->where('due_to', '<=', $date));
                    }),

                SelectFilter::make('user_id')
                    ->label(__('messages.general_todo.filters.user'))
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable(),
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

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if ($user && $user->hasPermissionTo('View All General Todos')) {
            return parent::getEloquentQuery();
        } else {
            return parent::getEloquentQuery()
                ->where("user_id", "=", Auth::id());
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGeneralTodos::route('/'),
            'create' => Pages\CreateGeneralTodo::route('/create'),
            'view' => Pages\ViewGeneralTodo::route('/{record}'),
            'edit' => Pages\EditGeneralTodo::route('/{record}/edit'),
        ];
    }
}
