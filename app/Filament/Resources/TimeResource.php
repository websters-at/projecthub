<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeResource\Pages;
use App\Filament\Resources\TimeResource\RelationManagers;
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
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\IconColumn\IconColumnSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
                        ->required()
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
                TextColumn::make('date')
                ->date(),
                TextColumn::make('description')->markdown()->limit(30)  ,
                TextColumn::make('start_time')
                    ->time(),
                TextColumn::make('end_time')
                    ->time(),
                TextColumn::make('end_time')
                    ->time(),
                TextColumn::make('total_hours_worked'),
                IconColumn::make('is_special')
                    ->icon(fn (bool $state): string => match ($state) {
                        false => 'fas-x',
                        true => 'fas-check',
                    })
                    ->color(fn (bool $state): string => match ($state) {
                        false => 'danger',
                        true => 'success',
                    })
                    ->size(IconColumnSize::Medium)
            ])
            ->filters([
                SelectFilter::make('contractClassification.user')
                    ->relationship('contractClassification.user', 'email')
                    ->label('Filter by User')
                    ->visible(function () {
                        return Auth::user()->hasPermissionTo('View Special Times Filters');
                    })
                    ->multiple()
                    ->searchable()
                    ->preload(),

                SelectFilter::make('contractClassification.contract')
                    ->relationship('contractClassification.contract', 'name')
                    ->label('Filter by Contract')
                    ->visible(function () {
                        return Auth::user()->hasPermissionTo('View Special Times Filters');
                    })
                    ->multiple()
                    ->searchable()
                    ->preload(),

                /* SelectFilter::make('contractClassification')
                     ->options(
                         User::all()
                             ->pluck('email', 'id')
                     )

                     ->visible(function () {
                         return Auth::user()->hasPermissionTo('View Special Times Filters');
                     })->preload()*/
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
