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

    public array $tableColumnSearches = []; // keep this

    protected function getTablePage(): string
    {
        return ListTimes::class;
    }

    protected function getStats(): array
    {
        $times = $this->getPageTableQuery()->get();

        $totalRawMinutes = $times->sum(function (Time $t) {
            return $t->total_hours_worked * 60 + $t->total_minutes_worked;
        });

        $specialMinutes = $times
            ->where('is_special', true)
            ->sum(function (Time $t) {
                return $t->total_hours_worked * 60 + $t->total_minutes_worked;
            });

        $totalRoundedMinutes = $times->sum(function (Time $t) {
            $minutes  = $t->total_hours_worked * 60 + $t->total_minutes_worked;
            $remainder = $minutes % 60;
            $base      = $minutes - $remainder;

            return $remainder >= 30
                ? $base + 60
                : $minutes;
        });

        $format = function (int $minutes): string {
            $h = intdiv($minutes, 60);
            $m = $minutes % 60;
            return "{$h}h {$m}min";
        };

        return [
            Stat::make(__('messages.time.stats.total_time_raw'), $format($totalRawMinutes))
                ->description(__('messages.time.stats.total_time_raw_description'))
                ->descriptionIcon('heroicon-o-clock')
                ->color('primary'),

            Stat::make(__('messages.time.stats.special_time'), $format($specialMinutes))
                ->description(__('messages.time.stats.special_time_description'))
                ->descriptionIcon('heroicon-o-star')
                ->color('warning'),

            Stat::make(__('messages.time.stats.entries_count'), $times->count())
                ->description(__('messages.time.stats.entries_count_description'))
                ->descriptionIcon('heroicon-o-document-text')
                ->color('success'),
        ];
    }
}
