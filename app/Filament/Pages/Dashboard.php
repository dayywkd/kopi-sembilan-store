<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filter Rentang Tanggal Penjualan')
                    ->description('Pilih tanggal mulai dan tanggal selesai untuk memfilter data penjualan secara real-time.')
                    ->schema([
                        DatePicker::make('startDate')
                            ->label('Tanggal Mulai')
                            ->native(false)
                            ->default(now()->subDays(30)->startOfDay()),
                        DatePicker::make('endDate')
                            ->label('Tanggal Selesai')
                            ->native(false)
                            ->default(now()->endOfDay()),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
            \App\Filament\Widgets\SalesChart::class,
            \App\Filament\Widgets\BestSellersWidget::class,
        ];
    }
}
