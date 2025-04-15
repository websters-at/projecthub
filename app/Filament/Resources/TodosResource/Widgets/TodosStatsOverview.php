<?php

namespace App\Filament\Resources\TodosResource\Widgets;

use App\Filament\Resources\TimeResource\Pages\ListTimes;
use App\Filament\Resources\TodosResource\Pages\ListTodos;
use App\Models\Todo;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TodosStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListTodos::class;
    }

    protected function getStats(): array
    {
        $baseQuery = $this->getPageTableQuery();

        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();
        $nextThreeDaysStart = Carbon::today()->addDays(1)->startOfDay();
        $nextThreeDaysEnd = Carbon::today()->addDays(3)->endOfDay();

        return [
            Stat::make(
                __('messages.todo.stats.today'),
                $baseQuery->clone()
                    ->whereBetween('due_to', [$todayStart, $todayEnd])
                    ->count()
            )
                ->description(__('messages.todo.stats.today_description'))
                ->descriptionIcon('heroicon-s-calendar'),

            Stat::make(
                __('messages.todo.stats.upcoming'),
                $baseQuery->clone()
                    ->whereBetween('due_to', [$nextThreeDaysStart, $nextThreeDaysEnd])
                    ->count()
            )
                ->description(__('messages.todo.stats.upcoming_description'))
                ->descriptionIcon('heroicon-s-calendar'),

            Stat::make(
                __('messages.todo.stats.open'),
                $baseQuery->clone()
                    ->where('is_done', false)
                    ->count()
            )
                ->description(__('messages.todo.stats.open_description'))
                ->descriptionIcon('heroicon-s-exclamation-circle'),
        ];
    }
}
