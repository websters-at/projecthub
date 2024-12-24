<?php

namespace App\Filament\Resources;

use Illuminate\Database\Eloquent\Model;

use App\Filament\Resources\CallNotesResource\Pages;
use App\Filament\Resources\CallNotesResource\RelationManagers;
use App\Models\Call;
use App\Models\CallNote;
use App\Models\CallNotes;
use App\Models\ContractClassification;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CallNotesResource extends Resource
{
    protected static ?string $model = CallNote::class;

    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Calls';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }
    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name', // Title of the call note
            'description', // Description of the call note

            // Related Call attributes
            'call.name', // Call name
            'call.contract_classification.contract.name', // Contract name
            'call.contract_classification.user.name', // User name
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        $callName = $record->call->name ?? 'No Call';
        return $record->name . ' (' . $callName . ')';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name')
                        ->label('Title of the call note')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Set a title for the call note'),
                    MarkdownEditor::make('description')
                        ->label('Description')
                        ->placeholder('Describe the note'),
                    Select::make('call_id')
                        ->label('Call Note')
                        ->options(function () {
                            $user = Auth::user();
                            return Call::whereHas('contract_classification', function ($query) use ($user) {
                                $query->where('user_id', $user->id);
                            })->pluck('name', 'id');
                        })
                        ->preload()
                        ->searchable()
                        ->required()

                ])->heading("General"),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->sortable()
                    ->searchable()
                    ->markdown(),

                Tables\Columns\TextColumn::make('call.name')
                    ->label('Call')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('call_id')
                    ->label('Call')
                    ->options(fn () => Call::all()->pluck('name', 'id'))
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

        if ($user && $user->hasPermissionTo('View All Call Notes')) {
            return parent::getEloquentQuery();
        } else {
            return parent::getEloquentQuery()
                ->whereHas('contracts.classifications', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                });
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCallNotes::route('/'),
            'create' => Pages\CreateCallNotes::route('/create'),
            'view' => Pages\ViewCallNotes::route('/{record}'),
            'edit' => Pages\EditCallNotes::route('/{record}/edit'),
        ];
    }
}
