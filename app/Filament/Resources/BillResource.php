<?php

namespace App\Filament\Resources;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\BillResource\Pages;
use App\Filament\Resources\BillResource\Pages\CreateBill;
use App\Filament\Resources\BillResource\Pages\EditBill;
use App\Filament\Resources\BillResource\Pages\ListBills;
use App\Filament\Resources\BillResource\Pages\ViewBill;
use App\Filament\Resources\BillResource\RelationManagers;
use App\Models\Bill;
use App\Models\Contract;
use App\Models\ContractClassification;
use App\Models\Customer;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists\Infolist;

class BillResource extends Resource
{
    protected static ?string $model = Bill::class;

    protected static ?string $navigationGroup = 'Contracts';
    protected static ?int $navigationSort = 5;


    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }
    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name', // Bill name
            'description',
            'hourly_rate',

            // Attributes from related ContractClassification
            'contractClassification.contract.name',
            'contractClassification.user.name',

            // Attributes from related Customer (via Contract)
            'contractClassification.contract.customer.company_name',
            'contractClassification.contract.customer.email',
        ];
    }
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        $contractName = $record->contractClassification->contract->name ?? 'No Contract';
        return $record->name . ' (' . $contractName . ')';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General')->schema(components: [
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('hourly_rate')
                        ->required()
                        ->numeric()
                        ->prefix("€"),
                    RichEditor::make('description')
                        ->nullable()
                        ->string(),
                    DatePicker::make('due_to'),
                    DatePicker::make('created_on')
                        ->nullable(),
                    Toggle::make('is_payed')
                        ->nullable()
                ])->collapsible()
                    ->collapsed(false),
                Section::make([
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
                ])->columns(1)
                    ->collapsible()
                    ->collapsed(false)
                    ->heading('Contract'),
                Section::make('Bill attachments')->schema([
                    FileUpload::make('attachments')
                        ->columns(1)
                        ->multiple()
                        ->disk('s3')
                        ->nullable()
                        ->directory('bills_attachments')
                        ->downloadable()
                        ->preserveFilenames()
                        ->previewable()
                ])->collapsible()
                    ->collapsed(false)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contractClassification.user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('contractClassification.contract.name')
                    ->label('Contract')
                    ->sortable()
                    ->limit(10)
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Bill Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(10)
                    ->markdown()
                    ->sortable(),
                TextColumn::make('hourly_rate')
                    ->label('€')
                    ->formatStateUsing(function ($state, $record) {
                        $roundedTotalHours = $record
                            ->contractClassification
                            ->times
                            ->map(function ($time) {
                                $hours = $time->total_hours_worked;
                                $minutes = $time->total_minutes_worked;
                                $fractionalHours = $minutes / 60;
                                return $fractionalHours >= 0.5 ? ceil($hours + $fractionalHours) : $hours + $fractionalHours;
                            })
                            ->sum();

                        $calculatedPrice = $state * $roundedTotalHours;
                        return $calculatedPrice . ' €';
                    }),
                Tables\Columns\ToggleColumn::make('is_payed')
                    ->label('Payed')
            ])
            ->filters([
                Filter::make('is_payed')
                    ->label('Payment Status')
                    ->query(fn(Builder $query) => $query->where('is_payed', true))
                    ->toggle()
            ])
            ->actions([
        ViewAction::make(),
        EditAction::make(),
        DeleteAction::make()
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

        if ($user && $user->hasPermissionTo('View All Bills')) {
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
            'index' => ListBills::route('/'),
            'create' => CreateBill::route('/create'),
            'view' => ViewBill::route('/{record}'),
            'edit' => EditBill::route('/{record}/edit')
        ];
    }
}
