<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CallResource\Pages;
use App\Filament\Resources\CallResource\RelationManagers;
use App\Models\Call;
use Filament\Tables\Columns\ToggleColumn;
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

    public static function getNavigationGroup(): ?string
    {
        return __('messages.call.resource.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.call.resource.name'); // Translated resource name
    }
    public static function getPluralLabel(): string
    {
        return __('messages.call.resource.name_plural');
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
                Section::make(__('messages.call.form.section_general'))->schema([ // Translated heading

                    TextInput::make('name')
                        ->required()
                        ->label(__('messages.call.form.field_name')), // Translated field
                    DateTimePicker::make('on_date')
                        ->default(now())
                        ->label(__('messages.call.form.field_on_date')) // Translated field
                        ->required(),
                    MarkdownEditor::make('description')
                        ->label(__('messages.call.form.field_description')) // Translated field
                        ->nullable(),
                    Toggle::make('is_done')
                        ->label(__('messages.call.form.field_is_done')) // Translated field
                        ->nullable(),
                ]),
                Section::make(__('messages.call.form.section_contract'))->schema([ // Translated heading
                    Select::make('contract_classification_id')
                        ->label(__('messages.call.form.field_contract')) // Translated field
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
                    ->label(__('messages.call.table.field_on_date')) // Translated field
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('contract_classification.contract.customer.company_name')
                    ->label(__('messages.call.table.field_customer')) // Translated field
                    ->sortable()
                    ->limit(10)
                    ->searchable(),
                TextColumn::make('contract_classification.contract.name')
                    ->label(__('messages.call.table.field_contract')) // Translated field
                    ->sortable()
                    ->limit(10)
                    ->searchable(),
                TextColumn::make('contract_classification.user.name')
                    ->label(__('messages.call.table.field_user')) // Translated field
                    ->sortable()
                    ->searchable(),
                ToggleColumn::make('is_done')
                    ->label(__('messages.call.table.field_is_done')) // Translated field
            ])
            ->filters([
                // Filter by completion status
                Tables\Filters\Filter::make('is_done')
                    ->label(__('messages.call.table.filter_is_done')) // Translated filter
                    ->query(fn(Builder $query) => $query->where('is_done', true))
                    ->toggle(),

                Tables\Filters\Filter::make('on_date')
                    ->label(__('messages.call.table.filter_on_date')) // Translated filter
                    ->form([
                        Forms\Components\DatePicker::make('on_date_from')->label(__('messages.call.table.filter_from')), // Translated field
                        Forms\Components\DatePicker::make('on_date_until')->label(__('messages.call.table.filter_until')), // Translated field
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['on_date_from'], fn($query, $date) => $query->whereDate('on_date', '>=', $date))
                            ->when($data['on_date_until'], fn($query, $date) => $query->whereDate('on_date', '<=', $date));
                    }),

                Tables\Filters\SelectFilter::make('contract_classification_id')
                    ->label(__('messages.call.table.filter_contract')) // Translated filter
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
