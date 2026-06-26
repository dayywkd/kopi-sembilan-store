<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $query = Order::query()
            ->whereIn('status', ['Paid', 'Packing', 'Shipped', 'Delivered']);

        if ($startDate) {
            $query->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        }

        if ($endDate) {
            $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        $totalRevenue = $query->sum('total_paid');
        $totalOrders = $query->count();

        // Cari total QTY produk terjual dalam rentang tanggal
        $orderIds = $query->pluck('id');
        $totalProductsSold = OrderItem::whereIn('order_id', $orderIds)->sum('quantity');

        return [
            Stat::make('Pendapatan Penjualan', 'Rp. ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Total omzet dari pesanan lunas/selesai')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Produk Terjual', number_format($totalProductsSold, 0, ',', '.'))
                ->description('Total unit kopi/barang terjual')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
            Stat::make('Total Transaksi', number_format($totalOrders, 0, ',', '.'))
                ->description('Total pesanan lunas/selesai')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('primary'),
        ];
    }
}
