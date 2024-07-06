<?php

namespace App\Filament\Resources\TimeResource\Pages;

use App\Filament\Resources\TimeResource;
use App\Filament\Resources\TimeResource\Widgets\TimesOverview;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListTimes extends ListRecords
{
    protected static string $resource = TimeResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            TimesOverview::class
        ];
    }
    protected function getFooterWidgets(): array
    {
        return [

        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()->label('All')->query(fn ($query) => $query),
            'this_week' => Tab::make()->label('This Week')->query(fn ($query) => $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])),
            'last_week' => Tab::make()->label('Last Week')->query(fn ($query) => $query->whereBetween('date', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])),
            'this_month' => Tab::make()->label('This Month')->query(fn ($query) => $query->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])),
            'last_month' => Tab::make()->label('Last Month')->query(fn ($query) => $query->whereBetween('date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])),
            'last_quarter' => Tab::make()->label('Last Quarter')->query(fn ($query) => $query->whereBetween('date', [now()->subQuarter()->startOfQuarter(), now()->subQuarter()->endOfQuarter()])),
            'this_year' => Tab::make()->label('This Year')->query(fn ($query) => $query->whereYear('date', now()->year)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
