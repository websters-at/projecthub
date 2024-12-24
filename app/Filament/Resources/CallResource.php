<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CallResource\Pages;
use App\Filament\Resources\CallResource\RelationManagers;
use App\Models\Call;
use Illuminate\Database\Eloquent\Model;
use App\Models\ContractClassification;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CallResource extends Resource
{
    protected static ?string $model = Call::class;
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationGroup = 'Calls';
    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name', // Call name
            'description', // Call description
            'on_date', // Call date

            // Related contract and customer details
            'contract_classification.contract.name', // Contract name
            'contract_classification.contract.customer.company_name', // Customer name
            'contract_classification.user.name', // User name associated with the call
        ];
    }



    public static function getGlobalSearchResultTitle(Model $record): string
    {
        $contractName = $record->contract_classification->contract->name ?? 'No Contract';
        $customerName = $record->contract_classification->contract->customer->company_name ?? 'No Customer';
        return $record->name . ' - ' . $contractName . ' (' . $customerName . ')';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General')->schema([
                    Toggle::make('is_done')
                        ->label('Done')
                        ->nullable(),
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
                        ->required()
                ])
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('on_date')
                    ->label('On Date')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('contract_classification.contract.customer.company_name')
                    ->label('Customer')
                    ->sortable()
                    ->limit(10)
                    ->searchable(),
                TextColumn::make('contract_classification.contract.name')
                    ->label('Contract')
                    ->sortable()
                    ->limit(10)
                    ->searchable(),
                TextColumn::make('contract_classification.user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_done')
                    ->label('Done')
            ])
            ->filters([
                // Filter by completion status
                Tables\Filters\Filter::make('is_done')
                    ->label('Completion Status')
                    ->query(fn (Builder $query) => $query->where('is_done', true))
                    ->toggle(),

                Tables\Filters\Filter::make('on_date')
                    ->label('Date Range')
                    ->form([
                        Forms\Components\DatePicker::make('on_date_from')->label('From'),
                        Forms\Components\DatePicker::make('on_date_until')->label('To'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['on_date_from'], fn ($query, $date) => $query->whereDate('on_date', '>=', $date))
                            ->when($data['on_date_until'], fn ($query, $date) => $query->whereDate('on_date', '<=', $date));
                    }),

                Tables\Filters\SelectFilter::make('contract_classification_id')
                    ->label('Contract')
                    ->options(function () {
                        $user = Auth::user();
                        return ContractClassification::where('user_id', $user->id)
                            ->with('contract')
                            ->get()
                            ->pluck('contract.name', 'id');
                    })
                    ->searchable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if ($user && $user->hasPermissionTo('View All Calls')) {
            return parent::getEloquentQuery();
        } else {
            return parent::getEloquentQuery()
                ->whereHas('contract_classification', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                });
        }
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
