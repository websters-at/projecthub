<?php


namespace App\Filament\Resources\ContractResource\Wigdets;

use App\Filament\Resources\ContractResource\Pages\ListContracts;
use App\Filament\Resources\TimeResource\Pages\ListTimes;
use App\Filament\Resources\TodosResource\Pages\ListTodos;
use App\Models\Contract;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContractStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListContracts::class;
    }
    protected function getStats(): array
    {
        $today = Carbon::today();
        $threeDaysFromNow = Carbon::today()->addDays(3);

        $baseQuery = $this->getPageTableQuery();

        return [
            Stat::make(
                __('messages.contract_stats.due_today'),
                $baseQuery->clone()->whereDate('due_to', $today)->count()
            )
                ->description(__('messages.contract_stats.due_today_desc'))
                ->descriptionIcon('heroicon-o-calendar'),

            Stat::make(
                __('messages.contract_stats.due_in_3_days'),
                $baseQuery->clone()->whereBetween('due_to', [$today, $threeDaysFromNow])->count()
            )
                ->description(__('messages.contract_stats.due_in_3_days_desc'))
                ->descriptionIcon('heroicon-o-calendar'),

            Stat::make(
                __('messages.contract_stats.completed'),
                $baseQuery->clone()->where('is_finished', true)->count()
            )
                ->description(__('messages.contract_stats.completed_desc'))
                ->descriptionIcon('heroicon-o-check'),
        ];
    }
}
