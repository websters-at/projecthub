<?php

namespace App\Filament\Resources\TimeResource\Widgets;

use App\Filament\Resources\TimeResource\Pages\ListTimes;
use App\Models\Time;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class TimesOverview extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    public array $tableColumnSearches = [];

    protected function getTablePage(): string
    {
        return ListTimes::class;
    }

    protected function getStats(): array
    {
        $times = $this->getPageTableQuery()->get();

        $totalRawMinutes = $times->sum(fn(Time $t) =>
            $t->total_hours_worked * 60 + $t->total_minutes_worked
        );

        $format = fn(int $minutes): string =>
            intdiv($minutes, 60).'h '.($minutes % 60).'min';

        $groupedByContract = $times->groupBy('contract_classification_id');
        $avgPerContract = $groupedByContract->count() > 0
            ? intval($groupedByContract->avg(fn($group) =>
            $group->sum(fn(Time $t) =>
                $t->total_hours_worked * 60 + $t->total_minutes_worked
            )
            ))
            : 0;


        $stats = [
            Stat::make(__('messages.time.stats.total_time_raw'), $format($totalRawMinutes))
                ->description(__('messages.time.stats.total_time_raw_description'))
                ->descriptionIcon('heroicon-o-clock')
               ,
        ];

        if (auth()->user()->hasRole("Admin")) {
            $unbilledMinutes = $times
                ->where('billed', false)
                ->sum(fn(Time $t) =>
                    $t->total_hours_worked * 60 + $t->total_minutes_worked
                );

            $stats[] = Stat::make(__('messages.time.stats.unbilled_time'), $format($unbilledMinutes))
                ->description(__('messages.time.stats.unbilled_time_description'))
                ->descriptionIcon('heroicon-o-currency-euro')
            ;

            $stats[] = Stat::make(__('messages.time.stats.avg_time_per_contract'), $format($avgPerContract))
                ->description(__('messages.time.stats.avg_time_per_contract_description'))
                ->descriptionIcon('heroicon-o-chart-bar')
      ;
        }
        else {
            $stats[] = Stat::make(__('messages.time.stats.avg_time_per_contract'), $format($avgPerContract))
                ->description(__('messages.time.stats.avg_time_per_contract_description'))
                ->descriptionIcon('heroicon-o-chart-bar')
      ;

            $stats[] = Stat::make(__('messages.time.stats.entries_count'), $times->count())
                ->description(__('messages.time.stats.entries_count_description'))
                ->descriptionIcon('heroicon-o-document-text')
               ;
        }

        return $stats;
    }
}