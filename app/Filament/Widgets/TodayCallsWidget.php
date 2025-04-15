<?php

namespace App\Filament\Widgets;

use App\Models\Call;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TodayCallsWidget extends BaseWidget
{
    protected static ?string $heading = 'Today\'s Calls';
    public static ?int $sort=2;
    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Call::query()
            ->whereDate('on_date', Carbon::today())
            ->when(
                !auth()->user()->hasRole('Admin'),
                function ($query) {
                    $query->whereHas('contract_classification.contract.users', function ($subQuery) {
                        $subQuery->where('user_id', auth()->id());
                    });
                }
            );
    }

    // Remove the canView() method to show widget to all users
    // protected static function canView(): bool
    // {
    //     return auth()->user()?->hasRole('Admin');
    // }

    protected function getTableColumns(): array
    {
        return [
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
                ->label(__('messages.call.table.field_is_done')),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('view')
                ->label('View Call')
                ->url(fn (Call $record): string => '/admin/calls/'. $record->id)
                ->openUrlInNewTab(),
        ];
    }
}