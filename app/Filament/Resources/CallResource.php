<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CallResource\Pages;
use App\Filament\Resources\CallResource\RelationManagers;
use App\Models\Call;
use App\Models\ContractClassification;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
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

class CallResource extends Resource
{
    protected static ?string $model = Call::class;
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'fas-phone';
    protected static ?string $navigationGroup = 'Calls';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General')->schema([
                    TextInput::make('name')
                        ->required()
                        ->label('Name'),
                    DateTimePicker::make('on_date')
                        ->default(now())
                        ->label('On Date')
                        ->required(),
                    MarkdownEditor::make('description')
                        ->label('Description')
                        ->nullable(),
                ]),
                Section::make('Contract')->schema([
                    Select::make('contract_classification_id')
                        ->label('Contract')
                        ->options(function () {
                            $user = Auth::user();
                            return ContractClassification::where('user_id', $user->id)
                                ->with('contract')
                                ->get()
                                ->pluck('contract.name', 'id');
                        })
                        ->preload()
                        ->searchable()

                ])
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCalls::route('/'),
            'create' => Pages\CreateCall::route('/create'),
            'view' => Pages\ViewCall::route('/{record}'),
            'edit' => Pages\EditCall::route('/{record}/edit'),
        ];
    }
}
