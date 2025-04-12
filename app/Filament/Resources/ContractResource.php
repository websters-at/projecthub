<?php

    namespace App\Filament\Resources;

    use App\Filament\Resources\ContractResource\Pages;
    use App\Filament\Resources\ContractResource\RelationManagers;
    use App\Models\Contract;
    use App\Models\ContractClassification;
    use App\Models\Customer;
    use App\Models\User;
    use Filament\Forms;
    use Filament\Forms\Components\DatePicker;
    use Filament\Forms\Components\DateTimePicker;
    use Filament\Forms\Components\FileUpload;
    use Filament\Forms\Components\RichEditor;
    use Filament\Forms\Components\Section;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\Split;
    use Filament\Forms\Components\Textarea;
    use Filament\Forms\Components\TextInput;
    use Filament\Forms\Components\Toggle;
    use Filament\Forms\Form;
    use Filament\Resources\Resource;
    use Filament\Tables;
    use Filament\Tables\Actions\BulkActionGroup;
    use Filament\Tables\Actions\DeleteAction;
    use Filament\Tables\Actions\DeleteBulkAction;
    use Filament\Tables\Actions\EditAction;
    use Filament\Tables\Actions\ViewAction;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Columns\ToggleColumn;
    use Filament\Tables\Filters\SelectFilter;
    use Filament\Tables\Table;
    use Illuminate\Database\Eloquent\Model;

    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\SoftDeletingScope;
    use Illuminate\Support\Facades\Auth;

    class ContractResource extends Resource
    {
        protected static ?string $model = Contract::class;
        protected static ?string $navigationIcon = 'fas-list-check';
        protected static ?string $navigationGroup = 'Contracts';
        protected static ?int $navigationSort = 2;

        public static function getNavigationBadge(): ?string
        {
            return static::$model::count();
        }

        public static function getGloballySearchableAttributes(): array
        {
            return [
                'name', // Contract name
                'description', // Contract description
                'priority', // Priority level of the contract
                'due_to', // Due date of the contract

                // Related customer and user details
                'customer.company_name', // Customer company name
                'users.name', // User (employee) name associated with the contract
                'users.email', // User (employee) email associated with the contract
            ];
        }
        public static function getGlobalSearchResultTitle(Model $record): string
        {
            $customerName = $record->customer->company_name ?? 'No Customer';
            return $record->name . ' - ' . $customerName;
        }


        public static function form(Form $form): Form
        {
            return $form
                ->schema([
                        Section::make('General')->schema([
                            Toggle::make('is_finished')
                                ->label("Done")
                                ->nullable(),
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            RichEditor::make('description')
                               ->nullable()
                                ->string()
                               ->maxLength(255),
                            Select::make('priority')
                                ->options([
                                    "low" => "Low",
                                    "high" => "High",
                                    "mid" => "Medium",
                                ])
                                ->searchable()
                                ->label('Priority')
                                ->required(),
                            DateTimePicker::make('due_to')
                            ->required(),

                        ])->collapsible()
                            ->collapsed(false),
                    Section::make('Customer')->schema([
                        Select::make('customer_id')
                            ->options(Customer::all()
                                ->pluck('company_name','id'))
                            ->searchable()
                        ->label('Customer')
                        ->required()
                    ])->columns(1)->collapsible()
                        ->collapsed(false),
                    Section::make('Employees')
                        ->schema([
                            Select::make('users')
                                ->multiple()
                                ->preload()
                                ->relationship('users', 'email')
                                ->searchable(),
                        ])
                        ->columns(1)->collapsible()
                        ->collapsed(false),

                        Section::make('Address')->schema([
                            TextInput::make('country')
                                ->nullable()
                                ->maxLength(255),
                            TextInput::make('state')
                                ->nullable()
                                ->maxLength(255),
                            TextInput::make('city')
                                ->nullable()
                                ->maxLength(255),
                             TextInput::make('zip_code')
                                 ->nullable()
                                 ->maxLength(255),
                            TextInput::make('address')
                                ->nullable()
                                ->maxLength(255)
                        ])->columns(2)->collapsible()
                            ->collapsed(false),

                    Section::make('Contract Picture')->schema([
                        FileUpload::make('attachments')
                        ->columns(1)
                        ->multiple()
                        ->nullable()->disk('s3')
                        ->directory('contracts_attachments')
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
                    TextColumn::make('due_to')
                        ->dateTime()
                        ->searchable()
                        ->sortable()
                        ->limit(30),
                    TextColumn::make('name')
                        ->searchable()
                        ->sortable()
                        ->limit(30),
                    TextColumn::make('customer.company_name')
                        ->limit(30)
                        ->searchable()
                        ->markdown(),
                    ToggleColumn::make('is_finished')
                        ->label('Done'),
                    TextColumn::make('priority')
                        ->searchable(),
                ])
                ->filters([
                    SelectFilter::make('customer')
                        ->relationship('customer', 'company_name')
                        ->searchable()
                        ->preload(),
                    SelectFilter::make('users')
                        ->relationship('users', 'email')
                        ->multiple()
                        ->searchable()
                        ->visible(function(): bool{
                            return Auth::user()->hasPermissionTo('View Special Contracts Filters');
                    })->preload(),
                    SelectFilter::make('priority')
                        ->options([
                            'low' => 'Low',
                            'mid' => 'Medium',
                            'high' => 'High',
                        ])
                        ->label('Priority')
                        ->searchable(),

                    Tables\Filters\Filter::make('due_to')
                        ->label('Due Date Range')
                        ->form([
                            DatePicker::make('due_from')->label('From'),
                            DatePicker::make('due_until')->label('To'),
                        ])
                        ->query(function (Builder $query, array $data) {
                            return $query
                                ->when($data['due_from'], fn ($query, $date) => $query->whereDate('due_to', '>=', $date))
                                ->when($data['due_until'], fn ($query, $date) => $query->whereDate('due_to', '<=', $date));
                        }),

                    Tables\Filters\Filter::make('is_finished')
                        ->label('Done')
                        ->query(fn (Builder $query) => $query->where('is_finished', true))
                        ->toggle(),

                    Tables\Filters\Filter::make('name')
                        ->label('Contract Company Name')
                        ->form([
                            TextInput::make('name_contains')->label('Name Contains'),
                        ])
                        ->query(function (Builder $query, array $data) {
                            return $query->when($data['name_contains'], fn ($query, $value) => $query->where('name', 'like', '%' . $value . '%'));
                        }),
                ])
                ->actions([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                ->bulkActions([
                    BulkActionGroup::make([
                        DeleteBulkAction::make(),
                    ]),
                ]);
        }

        public static function getRelations(): array
        {
            return [
                RelationManagers\TimesRelationManager::class,
                RelationManagers\NotesRelationManager::class,
                RelationManagers\BillsRelationManager::class,
                RelationManagers\LoginCredentialsRelationManager::class,
            ];
        }

        public static function getPages(): array
        {
            return [
                'index' => Pages\ListContracts::route('/'),
                'create' => Pages\CreateContract::route('/create'),
                'view' => Pages\ViewContract::route('/{record}'),
                'edit' => Pages\EditContract::route('/{record}/edit'),
            ];
        }

        public static function getEloquentQuery(): Builder
        {
            $user = auth()->user();

            if ($user && $user->hasPermissionTo('View All Contracts')) {
                return parent::getEloquentQuery();
            } else {
                return parent::getEloquentQuery()
                    ->whereHas('classifications', function (Builder $query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
            }
        }
    }
