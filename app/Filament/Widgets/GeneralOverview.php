<?php

namespace App\Filament\Widgets;

use App\Models\Bill;
use App\Models\Call;
use App\Models\Contract;
use App\Models\GeneralTodo;
use App\Models\Todo;
use App\Models\Time;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class GeneralOverview extends BaseWidget
{


    protected function getStats(): array
    {
        $stats = [];


        $unpaidBillsTotal = Bill::where('is_payed', false)
            ->get()
            ->sum(function (Bill $bill) {
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


        if (Auth::user()->hasRole('Admin')) {
            $stats[] = Stat::make(__('messages.general_overview.todays_calls'), Call::whereDate('on_date', Carbon::today())->count())
                ->description(__('messages.general_overview.todays_calls_description'))
                ->descriptionIcon('heroicon-o-phone');

            $stats[] = Stat::make(__('messages.general_overview.unpaid_amount'), number_format($unpaidBillsTotal, 2) . ' €')
                ->description(__('messages.general_overview.unpaid_amount_description'))
                ->descriptionIcon('heroicon-o-banknotes');
            $unbilledMinutes = Time::where('billed', false)
                ->get()
                ->sum(fn(Time $t) => $t->total_hours_worked * 60 + $t->total_minutes_worked);
            $format = fn(int $m) => intdiv($m, 60) . 'h ' . ($m % 60) . 'min';
            $stats[] = Stat::make(__('messages.general_overview.unbilled_time'), $format($unbilledMinutes))
                ->description(__('messages.general_overview.unbilled_time_description'))
                ->descriptionIcon('heroicon-o-clock');


            $stats[] = Stat::make(__('messages.general_overview.contracts_due_3_days'), Contract::whereBetween('due_to', [Carbon::today(), Carbon::today()->addDays(3)])->count())
                ->description(__('messages.general_overview.contracts_due_3_days_description'))
                ->descriptionIcon('fas-list-check');

            $stats[] = Stat::make(__('messages.general_overview.todos_due_3_days'), Todo::whereBetween('due_to', [Carbon::today(), Carbon::today()->addDays(3)])->count())
                ->description(__('messages.general_overview.todos_due_3_days_description'))
                ->descriptionIcon('heroicon-o-check-circle');

            $stats[] = Stat::make(__('messages.general_overview.general_todos_due_3_days'), GeneralTodo::whereBetween('due_to', [Carbon::today(), Carbon::today()->addDays(3)])->count())
                ->description(__('messages.general_overview.general_todos_due_3_days_description'))
                ->descriptionIcon('heroicon-o-clipboard-document');
        }

        return $stats;
    }
}
