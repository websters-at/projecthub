<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeneralTodoResource\Pages;
use App\Filament\Resources\GeneralTodoResource\RelationManagers;
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
    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    DateTimePicker::make('due_to')
                        ->required(),
                    MarkdownEditor::make('description'),
                    Toggle::make('is_done')
                        ->label('Completed')
                        ->default(false),
                    Select::make('priority')
                        ->options([
                            'low' => 'Low',
                            'mid' => 'Medium',
                            'high' => 'High',
                        ])
                        ->default('medium')
                        ->required(),
                    FileUpload::make('attachments')
                        ->multiple()
                        ->disk('s3')
                        ->acceptedFileTypes(['image/*', 'application/pdf', 'text/plain'])
                        ->directory('todos_attachments')
                ])->heading("General"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                TextColumn::make('due_to')
                    ->time()
                    ->sortable(),
                TextColumn::make('priority')
                    ->sortable(),
                ToggleColumn::make('is_done')
                    ->label('Completed')
                    ->sortable(),
            ])
            ->filters([
               SelectFilter::make('priority')
                    ->label('Priority')
                    ->options([
                        'low' => 'Low',
                        'mid' => 'Medium',
                        'high' => 'High',
                    ])
                    ->searchable(),
                Filter::make('is_done')
                    ->label('Completion Status')
                    ->query(fn (Builder $query) => $query->where('is_done', true))
                    ->toggle(),
                Filter::make('due_to')
                    ->label('Due Date Range')
                    ->form([
                        DateTimePicker::make('due_from')->label('From'),
                        DateTimePicker::make('due_until')->label('To'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['due_from'], fn ($query, $date) => $query->where('due_to', '>=', $date))
                            ->when($data['due_until'], fn ($query, $date) => $query->where('due_to', '<=', $date));
                    }),

                SelectFilter::make('user_id')
                    ->label('User')
                    ->options(
                        User::all()->pluck('name', 'id')
                    )
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
