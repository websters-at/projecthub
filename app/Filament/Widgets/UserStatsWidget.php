<?php
namespace App\Filament\Widgets;

use App\Models\Bill;
use App\Models\ContractClassification;
use App\Models\Todo;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class UserStatsWidget extends BaseWidget
{
    public static function canView(): bool
    {
        return !Auth::user()->hasRole('Admin');
    }

    protected function getStats(): array
    {
        $user = Auth::user();

        $contractClassificationIds = ContractClassification::where('user_id', $user->id)->pluck('id');

        $unpaidTotal = Bill::whereIn('contract_classification_id', $contractClassificationIds)
            ->where('is_payed', false)
            ->get()
            ->sum(function (Bill $bill) {
                if ($bill->is_flat_rate) {
                    return $bill->flat_rate_amount;
                } else {
                    $hours = $bill->contractClassification
                        ->times
                        ->map(fn($time) => $time->total_hours_worked + $time->total_minutes_worked / 60)
                        ->map(fn($h) => ($h - floor($h)) >= 0.5 ? ceil($h) : $h)
                        ->sum();

                    return $bill->hourly_rate * $hours;
                }
            });

        $totalContracts = ContractClassification::where('user_id', $user->id)->count();

        // Count of open todos for the user
        $openTodosCount = Todo::where('is_done', false)
            ->whereHas('contract', function ($query) use ($user) {
                $query->whereHas('contract_classifications', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->count();

        return [
            Stat::make(__('messages.user_stats.unpaid_bills'), number_format($unpaidTotal, 2) . ' €')
                ->description(__('messages.user_stats.unpaid_bills_description'))
                ,

            Stat::make(__('messages.user_stats.your_contracts'), $totalContracts)
                ->description(__('messages.user_stats.your_contracts_description'))
              ,

            // Add the open todos stat
            Stat::make(__('messages.user_stats.open_todos'), $openTodosCount)
                ->description(__('messages.user_stats.open_todos_description'))
                ,
        ];
    }
}
