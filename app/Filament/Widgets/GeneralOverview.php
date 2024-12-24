<?php

namespace App\Filament\Widgets;

use App\Models\Bill;
use App\Models\Call;
use App\Models\Contract;
use App\Models\GeneralTodo;
use App\Models\Todo;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GeneralOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $todaysCallsCount = Call::whereDate('on_date', Carbon::today())->count();
        $unpaidBillsCount = Bill::where('is_payed', false)->count();

        $upcomingContractsCount = Contract::whereBetween('due_to', [
            Carbon::today(),
            Carbon::today()->addDays(3),
        ])->count();
        $todosDueCount = Todo::whereBetween('due_to', [
            Carbon::today(),
            Carbon::today()->addDays(3),
        ])->count();
        $generalTodosDueCount = GeneralTodo::whereBetween('due_to', [
            Carbon::today(),
            Carbon::today()->addDays(3),
        ])->count();

        return [
            Stat::make('Today\'s Calls', $todaysCallsCount)
                ->description('Total calls created today')
                ->descriptionIcon('heroicon-o-phone')
                ->color('success'),
            Stat::make('Unpaid Bills', $unpaidBillsCount)
                ->description('Total unpaid bills')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('danger'),
            Stat::make('Contracts Due in 3 Days', $upcomingContractsCount)
                ->description('Contracts nearing due date')
                ->descriptionIcon('fas-list-check')
                ->color('warning'),
            Stat::make('Todos Due in 3 Days', $todosDueCount)
                ->description('Todos nearing due date')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('primary'),
            Stat::make('General Todos Due in 3 Days', $generalTodosDueCount)
                ->description('General todos nearing due date')
                ->descriptionIcon('heroicon-o-clipboard-document')
                ->color('secondary'),
        ];
    }
}
