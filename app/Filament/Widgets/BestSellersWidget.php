<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BestSellersWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Penjualan Produk Terlaris';

    protected int | string | array $columnSpan = [
        'md' => 1,
        'lg' => 1,
    ];

    public function table(Table $table): Table
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : now()->subDays(30)->startOfDay();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : now()->endOfDay();

        $query = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.id as id',
                'products.name as product_name',
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as qty_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->whereIn('orders.status', ['Paid', 'Packing', 'Shipped', 'Delivered'])
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('products.id', 'products.name', 'categories.name')
            ->orderBy('qty_sold', 'desc');

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Nama Produk')
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category_name')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('qty_sold')
                    ->label('QTY')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('total_revenue')
                    ->label('Omzet')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->alignRight(),
            ])
            ->paginated([5, 10]);
    }
}
