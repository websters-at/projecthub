<?php

namespace App\Filament\Widgets;

use App\Models\Call;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class TodayCallsWidget extends BaseWidget
{
    protected static ?string $heading = 'Today\'s Calls';

    // Ensure the widget spans the full width of the dashboard
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('Admin');
    }

    protected function getTableQuery(): Builder
    {
        return Call::whereDate('on_date', Carbon::today())
            ->where('is_done', false);
    }

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
            Tables\Actions\ViewAction::make(),
        ];
    }
}
