<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalesChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Grafik Hasil Penjualan (Harian)';
    
    protected int | string | array $columnSpan = [
        'md' => 1,
        'lg' => 1,
    ];

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : now()->subDays(30)->startOfDay();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : now()->endOfDay();

        $salesData = Order::query()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_paid) as total_sales')
            )
            ->whereIn('status', ['Paid', 'Packing', 'Shipped', 'Delivered'])
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $dataMap = $salesData->pluck('total_sales', 'date')->toArray();
        $labels = [];
        $data = [];

        $current = $start->copy();
        while ($current->lte($end)) {
            $dateStr = $current->format('Y-m-d');
            $labels[] = $current->isoFormat('D MMM');
            $data[] = $dataMap[$dateStr] ?? 0;
            $current->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Omzet Harian (Rp)',
                    'data' => $data,
                    'borderColor' => '#D97706',
                    'backgroundColor' => 'rgba(217, 119, 6, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
