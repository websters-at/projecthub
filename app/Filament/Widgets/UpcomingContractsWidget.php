<?php

namespace App\Filament\Widgets;

use App\Models\Contract; // Assuming you have a Contract model
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class UpcomingContractsWidget extends BaseWidget
{
    use InteractsWithTable;

    protected static ?string $heading = 'Upcoming Contracts';

    public static ?int $sort=2;

    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Contract::query()
            ->whereBetween('due_to', [Carbon::today(), Carbon::today()->addDays(3)])
            ->when(
                !auth()->user()->hasRole('Admin'),
                fn ($query) => $query->whereHas('users',
                    fn ($sub) => $sub->where('user_id', auth()->id())
                )
            );
    }

    // Keep the original getTableColumns() implementation
    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('due_to')
                ->label(__('messages.contract.table.due_to'))
                ->dateTime()
                ->searchable()
                ->sortable()
                ->limit(30),
            TextColumn::make('name')
                ->label(__('messages.contract.table.name'))
                ->searchable()
                ->sortable()
                ->limit(30),
            TextColumn::make('customer.company_name')
                ->label(__('messages.contract.table.customer'))
                ->limit(30)
                ->searchable()
                ->markdown(),
            ToggleColumn::make('is_finished')
                ->label(__('messages.contract.table.is_finished')),
            TextColumn::make('priority')
                ->label(__('messages.contract.table.priority'))
                ->searchable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('view')
                ->label('View Contract')
                ->url(fn (Contract $record): string => '/admin/contracts/' . $record->id)
                ->openUrlInNewTab(),
        ];
    }
}
