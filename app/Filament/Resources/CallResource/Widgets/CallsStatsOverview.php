<?php

namespace App\Filament\Resources\CallResource\Widgets;

use App\Models\Call;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CallsStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getStats(): array
    {
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();
        $nextThreeDaysStart = Carbon::today()->addDays(1)->startOfDay();
        $nextThreeDaysEnd = Carbon::today()->addDays(3)->endOfDay();

        return [
            Stat::make(__('messages.call.stats.today'), Call::whereBetween('on_date', [$todayStart, $todayEnd])->count())
                ->description(__('messages.call.stats.today_description'))
                ->descriptionIcon('heroicon-s-calendar'),
            Stat::make(__('messages.call.stats.upcoming'), Call::whereBetween('on_date', [$nextThreeDaysStart, $nextThreeDaysEnd])->count())
                ->description(__('messages.call.stats.upcoming_description'))
                ->descriptionIcon('heroicon-s-calendar'),
            Stat::make(__('messages.call.stats.open'), Call::where('is_done', false)->count())
                ->description(__('messages.call.stats.open_description'))
                ->descriptionIcon('heroicon-s-exclamation-circle'),
        ];
    }
}
