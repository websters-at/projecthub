<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CallResource\Pages;
use App\Filament\Resources\CallResource\RelationManagers;
use App\Filament\Resources\CallResource\RelationManagers\CallNotesRelationManagerRelationManager;
use App\Models\Call;
use App\Models\Customer;
use Filament\Forms\Components\Actions\Action;
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
use SebastianBergmann\CodeCoverage\Filter;

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
        return __('messages.call.resource.name');
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
                Section::make(__('messages.call.form.section_general'))->schema([
                    Select::make('customer_id')
                        ->label(__('messages.call.form.field_customer'))
                        ->options(Customer::all()->pluck('company_name', 'id'))
                        ->searchable()
                        ->preload()
                        ->nullable(),
                    TextInput::make('name')
                        ->required()
                        ->label(__('messages.call.form.field_name')),
                    DateTimePicker::make('on_date')
                        ->default(now())
                        ->label(__('messages.call.form.field_on_date'))
                        ->required(),
                    MarkdownEditor::make('description')
                        ->label(__('messages.call.form.field_description'))
                        ->nullable(),
                    Toggle::make('is_done')
                        ->label(__('messages.call.form.field_is_done'))
                        ->nullable(),
                ]),
                Section::make(__('messages.call.form.section_contract'))->schema([
                    Select::make('contract_classification_id')
                        ->label(__('messages.call.form.field_contract'))
                        ->options(function () {
                            $user = Auth::user();
                            if ($user->hasRole('Admin')) {
                                return ContractClassification::with('contract')
                                    ->get()
                                    ->pluck('contract.name', 'id');
                            } else {
                                return ContractClassification::where('user_id', $user->id)
                                    ->with('contract')
                                    ->get()
                                    ->pluck('contract.name', 'id');
                            }
                        })
                        ->preload()
                        ->searchable(),
                ]),
                Section::make(__('messages.contract.form.section_location'))->schema([
                    TextInput::make('country')->label(__('messages.contract.form.field_country'))->nullable()->maxLength(255),
                    TextInput::make('state')->label(__('messages.contract.form.field_state'))->nullable()->maxLength(255),
                    TextInput::make('city')->label(__('messages.contract.form.field_city'))->nullable()->maxLength(255),
                    TextInput::make('zip_code')->label(__('messages.contract.form.field_zip_code'))->nullable()->maxLength(255),
                    TextInput::make('address')->label(__('messages.contract.form.field_address'))->nullable()->maxLength(255),
                ])->columns(2)->collapsible()->collapsed(false),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('on_date')
                    ->label(__('messages.call.table.field_on_date'))
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customer.company_name')
                    ->label(__('messages.call.table.field_customer'))
                    ->sortable()
                    ->limit(25)
                    ->searchable(),
                TextColumn::make('contract_classification.contract.name')
                    ->label(__('messages.call.table.field_contract'))
                    ->sortable()
                    ->limit(25)
                    ->searchable(),
                ToggleColumn::make('is_done')
                    ->label(__('messages.call.table.field_is_done'))
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('contract_classification_id')
                    ->label(__('messages.call.table.filter_contract')) // Translated filter
                    ->options(function () {
                        $user = Auth::user();

                        if ($user->hasRole('Admin')) {
                            return ContractClassification::with('contract')
                                ->get()
                                ->pluck('contract.name', 'id');
                        }

                        return ContractClassification::where('user_id', $user->id)
                            ->with('contract')
                            ->get()
                            ->pluck('contract.name', 'id');
                    })
                    ->searchable(),
                Tables\Filters\SelectFilter::make('customer_id')
                    ->label(__('messages.call.table.filter_customer')) // Translated filter label
                    ->relationship('customer', 'company_name') // Assumes 'customer' relationship exists in Call model
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('is_done')
                    ->label(__('messages.call.table.filter_is_done'))
                    ->query(fn(Builder $query) => $query->where('is_done', true))
                    ->toggle(),

                Tables\Filters\Filter::make('on_date')
                    ->label(__('messages.call.table.filter_on_date'))
                    ->form([
                        Forms\Components\DatePicker::make('on_date_from')->label(__('messages.call.table.filter_from')),
                        Forms\Components\DatePicker::make('on_date_until')->label(__('messages.call.table.filter_until')),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['on_date_from'], fn($query, $date) => $query->whereDate('on_date', '>=', $date))
                            ->when($data['on_date_until'], fn($query, $date) => $query->whereDate('on_date', '<=', $date));
                    }),
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
           CallNotesRelationManagerRelationManager::class
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
