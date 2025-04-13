<?php

namespace App\Filament\Resources;
use Carbon\Carbon;
use App\Filament\Resources\TimeResource\Pages;
use App\Filament\Resources\TimeResource\RelationManagers;
use App\Filament\Resources\TimeResource\Widgets\TimesOverview;
use App\Models\Contract;
use App\Models\ContractClassification;
use App\Models\Time;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\IconColumn\IconColumnSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;

class TimeResource extends Resource
{
    protected static ?string $model = Time::class;

    protected static ?string $navigationIcon = 'far-clock';
    protected static ?string $navigationGroup = 'Contracts';
    protected static ?int $navigationSort = 4;
    public static function getNavigationGroup(): ?string
    {
        return __('messages.time.resource.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.time.resource.name');
    }

    public static function getPluralLabel(): string
    {
        return __('messages.time.resource.name_plural');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    DateTimePicker::make('date')
                        ->default(now())
                        ->required()
                        ->label(__('messages.time.form.field_date')), // translated label
                    RichEditor::make('description')
                        ->nullable()
                        ->string()
                        ->label(__('messages.time.form.field_description')), // translated label
                ])->heading(__('messages.time.form.general'))->collapsible()->collapsed(false),
                Section::make([
                    TextInput::make('total_hours_worked')
                        ->required()
                        ->label(__('messages.time.form.field_total_hours_worked')), // translated label
                    TextInput::make('total_minutes_worked')
                        ->label(__('messages.time.form.field_total_minutes_worked')), // translated label
                ])->columns(2)
                    ->collapsible()
                    ->collapsed(false)
                    ->heading(__('messages.time.form.time')),
                Section::make([
                    Select::make('contract_classification_id')
                        ->label(__('messages.time.form.field_contract_label')) // translated label
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
                    ->heading(__('messages.time.form.contract')),
                Section::make([
                    Toggle::make('is_special')
                        ->default(false)
                        ->label(__('messages.time.form.field_is_special')) // translated label
                ])->columns(1)
                    ->collapsible()
                    ->collapsed(false)
                    ->heading(__('messages.time.form.specification'))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label(__('messages.time.table.date')) // Translated label
                    ->date(),
                TextColumn::make('description')
                    ->label(__('messages.time.table.description')) // Translated label
                    ->markdown()
                    ->limit(30),
                TextColumn::make('total_hours_worked')
                    ->label(__('messages.time.table.total_hours')) // Translated label
                    ->sortable(),
                TextColumn::make('total_minutes_worked')
                    ->label(__('messages.time.table.total_minutes')) // Translated label
                    ->sortable(),
                IconColumn::make('is_special')
                    ->label(__('messages.time.table.is_special')) // Translated label
                    ->icon(fn (bool $state): string => $state ? 'fas-check' : 'fas-x')
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                    ->size(IconColumnSize::Medium)
            ])
            ->filters([
                SelectFilter::make('contractClassificationUser')
                    ->relationship('contractClassification.user', 'email')
                    ->label(__('messages.time.filters.contract_classification_user')) // Translated label
                    ->visible(fn() => Auth::user()->hasPermissionTo('View Special Times Filters'))
                    ->multiple()
                    ->searchable()
                    ->preload(),

                SelectFilter::make('contractClassificationContract')
                    ->relationship('contractClassification.contract', 'name')
                    ->label(__('messages.time.filters.contract_classification_contract')) // Translated label
                    ->visible(fn() => Auth::user()->hasPermissionTo('View Special Times Filters'))
                    ->multiple()
                    ->searchable()
                    ->preload(),

                Filter::make('date')
                    ->form([
                        DatePicker::make('from')
                            ->label(__('messages.time.filters.date_from')), // Translated label
                        DatePicker::make('until')
                            ->label(__('messages.time.filters.date_until')), // Translated label
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date))
                            ->when($data['until'], fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators[] = Indicator::make(__('messages.time.filters.date_from') . ' ' . Carbon::parse($data['from'])->toFormattedDateString())
                                ->removeField('from');
                        }

                        if ($data['until'] ?? null) {
                            $indicators[] = Indicator::make(__('messages.time.filters.date_until') . ' ' . Carbon::parse($data['until'])->toFormattedDateString())
                                ->removeField('until');
                        }

                        return $indicators;
                    }),
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

        if ($user && $user->hasPermissionTo('View All Times')) {
            return parent::getEloquentQuery();
        } else {
            return parent::getEloquentQuery()
                ->whereHas('contractClassification', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                });
        }
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimes::route('/'),
            'create' => Pages\CreateTime::route('/create'),
            'view' => Pages\ViewTime::route('/{record}'),
            'edit' => Pages\EditTime::route('/{record}/edit'),
        ];
    }
}
