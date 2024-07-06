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
    protected static ?string $navigationGroup = 'Time Tracking';
    protected static ?string $navigationIcon = 'far-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    DatePicker::make('date')
                        ->required(),
                    RichEditor::make('description')
                        ->nullable()
                        ->string()
                        ->maxLength(255),
                ])->heading('General')
                    ->collapsible()
                    ->collapsed(false),
                Section::make([
                    TimePicker::make('start_time')
                        ->required(),
                    TimePicker::make('end_time')
                        ->required(),
                    TextInput::make('total_hours_worked')
                        ->required(),
                    TextInput::make('total_minutes_worked')
                ])->columns(2)
                    ->collapsible()
                    ->collapsed(false)
                    ->heading('Time'),
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
                Section::make([
                    Toggle::make('is_special')
                    ->default(false)
                ])->columns(1)
                    ->collapsible()
                    ->collapsed(false)
                    ->heading('Specification')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')->date(),
                TextColumn::make('description')->markdown()->limit(30),
                TextColumn::make('start_time')->time(),
                TextColumn::make('end_time')->time(),
                TextColumn::make('total_hours_worked'),
                IconColumn::make('is_special')
                    ->icon(fn (bool $state): string => $state ? 'fas-check' : 'fas-x')
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                    ->size(IconColumnSize::Medium)
            ])
            ->filters([
                SelectFilter::make('contractClassificationUser')
                    ->relationship('contractClassification.user', 'email')
                    ->label('Filter by User')
                    ->visible(fn() => Auth::user()->hasPermissionTo('View Special Times Filters'))
                    ->multiple()
                    ->searchable()
                    ->preload(),

                SelectFilter::make('contractClassificationContract')
                    ->relationship('contractClassification.contract', 'name')
                    ->label('Filter by Contract')
                    ->visible(fn() => Auth::user()->hasPermissionTo('View Special Times Filters'))
                    ->multiple()
                    ->searchable()
                    ->preload(),

                Filter::make('date')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date))
                            ->when($data['until'], fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators[] = Indicator::make('Date from ' . Carbon::parse($data['from'])->toFormattedDateString())
                                ->removeField('from');
                        }

                        if ($data['until'] ?? null) {
                            $indicators[] = Indicator::make('Date until ' . Carbon::parse($data['until'])->toFormattedDateString())
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
