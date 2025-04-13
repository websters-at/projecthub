<?php

namespace App\Filament\Resources\BillResource\Widgets;

use App\Filament\Resources\BillResource\Pages\ListBills;
use App\Models\Bill;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class BillStatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListBills::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $total = $query->get()->sum(function (Bill $bill) {
            if ($bill->is_flat_rate) {
                return $bill->flat_rate_amount;
            }

            $hours = $bill->contractClassification
                ->times
                ->map(fn($time) => $time->total_hours_worked + $time->total_minutes_worked / 60)
                ->map(fn($h) => ($h - floor($h)) >= 0.5 ? ceil($h) : $h)
                ->sum();

            return $bill->hourly_rate * $hours;
        });

        $totalUnpaid = $query->get()->where('is_payed', false)->sum(function (Bill $bill) {
            if ($bill->is_flat_rate) {
                return $bill->flat_rate_amount;
            }

            $hours = $bill->contractClassification
                ->times
                ->map(fn($time) => $time->total_hours_worked + $time->total_minutes_worked / 60)
                ->map(fn($h) => ($h - floor($h)) >= 0.5 ? ceil($h) : $h)
                ->sum();

            return $bill->hourly_rate * $hours;
        });

        $totalPaid = $query->get()->where('is_payed', true)->sum(function (Bill $bill) {
            if ($bill->is_flat_rate) {
                return $bill->flat_rate_amount;
            }

            $hours = $bill->contractClassification
                ->times
                ->map(fn($time) => $time->total_hours_worked + $time->total_minutes_worked / 60)
                ->map(fn($h) => ($h - floor($h)) >= 0.5 ? ceil($h) : $h)
                ->sum();

            return $bill->hourly_rate * $hours;
        });

        return [
            Stat::make('Total Amount', number_format($total, 2) . ' €'),
            Stat::make('Total Unpaid Amount', number_format($totalUnpaid, 2) . ' €'),
            Stat::make('Total Paid Amount', number_format($totalPaid, 2) . ' €'),
        ];
    }
}
