<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoteResource\Pages;
use App\Models\Contract;
use App\Models\ContractClassification;
use App\Models\ContractNote;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ContractNoteResource extends Resource
{
    protected static ?string $model = ContractNote::class;

    protected static ?string $navigationGroup = 'Contracts';
    protected static ?int $navigationSort = 6;

    protected static ?string $navigationIcon = 'fas-book';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Information')->schema([
                    TextInput::make('name')
                        ->label('Title of the note')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Set a title for the note'),

                    MarkdownEditor::make('description')
                        ->label('Description')
                        ->placeholder('Describe the note'),

                    DateTimePicker::make('date')
                        ->label('Date')
                        ->default(now())
                        ->required(),
                ]),

                Section::make('More Information')->schema([
                    Select::make('contract_classification_id')
                        ->label('Contract')
                        ->required()
                        ->options(function () {
                            $user = Auth::user();
                            return ContractClassification::where('user_id', Auth::user()->id)
                                ->with('contract')
                                ->get()
                                ->pluck('contract.name', 'id');
                        }),
                    FileUpload::make('attachments')
                        ->label('Attachments')
                        ->multiple()
                        ->directory('contracts_notes')
                        ->preserveFilenames()
                        ->acceptedFileTypes(['application/pdf', 'image/*', 'text/plain'])
                        ->maxSize(5120)
                        ->hint('Acceted formats: PDF oder Bilder.'),
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Date')
                    ->sortable(),
                TextColumn::make('contractClassification.contract.name')
                    ->label('Contract')
                    ->sortable()
                    ->limit(10)
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            return parent::getEloquentQuery();
        } else {
            return parent::getEloquentQuery()
                ->whereHas('contractClassification', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                });
        }
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotes::route('/'),
            'create' => Pages\CreateNote::route('/create'),
            'view' => Pages\ViewNote::route('/{record}'),
            'edit' => Pages\EditNote::route('/{record}/edit'),
        ];
    }
}
