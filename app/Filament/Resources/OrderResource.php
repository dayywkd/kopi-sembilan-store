<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('courier')
                            ->disabled()
                            ->label('Metode Pengiriman')
                            ->dehydrated(false)
                            ->formatStateUsing(fn ($state) => $state === 'pickup' ? 'Ambil di Toko (Local Pickup)' : (empty($state) ? 'Kirim Kurir' : 'Kirim Kurir (' . strtoupper($state) . ')')),
                        Forms\Components\Select::make('status')
                            ->options(function ($get) {
                                $isPickup = $get('courier') === 'pickup';
                                return [
                                    'Awaiting Payment' => 'Awaiting Payment',
                                    'Paid' => 'Paid (Lunas)',
                                    'Packing' => $isPickup ? 'Packing (Sedang Disiapkan)' : 'Packing (Sedang Dikemas)',
                                    'Shipped' => $isPickup ? 'Ready For Pickup (Siap Diambil)' : 'Shipped (Dalam Pengiriman)',
                                    'Delivered' => $isPickup ? 'Picked Up (Telah Diambil)' : 'Delivered (Pesanan Tiba)',
                                    'Cancelled' => 'Cancelled',
                                ];
                            })
                            ->required(),
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'Transfer BCA' => 'Transfer BCA',
                                'Transfer BRI' => 'Transfer BRI',
                                'QRIS' => 'QRIS',
                                'Bank Transfer' => 'Bank Transfer (Lama)',
                            ])
                            ->label('Metode Pembayaran')
                            ->required(),
                        Forms\Components\TextInput::make('tracking_number')
                            ->maxLength(255)
                            ->label('Nomor Resi / Tracking Number')
                            ->hidden(fn ($get) => $get('courier') === 'pickup'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable()
                    ->fontFamily(\Filament\Support\Enums\FontFamily::Mono),
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Nama')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function ($q) use ($search) {
                            $q->where('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable()
                    ->formatStateUsing(fn ($record) => trim($record->first_name . ' ' . $record->last_name)),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_paid')
                    ->numeric(decimalPlaces: 0, decimalSeparator: ',', thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(function (string $state, $record): string {
                        if ($record->courier === 'pickup') {
                            return match ($state) {
                                'Packing' => 'Packing (Prep)',
                                'Shipped' => 'Ready For Pickup',
                                'Delivered' => 'Picked Up',
                                default => $state
                            };
                        }
                        return $state;
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Awaiting Payment' => 'warning',
                        'Paid' => 'info',
                        'Packing' => 'gray',
                        'Shipped' => 'primary',
                        'Delivered' => 'success',
                        'Cancelled' => 'danger',
                        default => 'neutral'
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('courier')
                    ->label('Metode / Kurir')
                    ->badge()
                    ->formatStateUsing(function (?string $state): string {
                        if (empty($state)) {
                            return 'Kirim Kurir';
                        }
                        return $state === 'pickup' ? 'Ambil di Toko' : 'Kirim Kurir (' . strtoupper($state) . ')';
                    })
                    ->color(fn (?string $state): string => $state === 'pickup' ? 'success' : 'primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode Bayar')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Transfer BCA' => 'info',
                        'Transfer BRI' => 'success',
                        'QRIS' => 'warning',
                        'Bank Transfer' => 'gray',
                        default => 'neutral'
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Awaiting Payment' => 'Awaiting Payment',
                        'Paid' => 'Paid',
                        'Packing' => 'Packing',
                        'Shipped' => 'Shipped',
                        'Delivered' => 'Delivered',
                        'Cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('delivery_method')
                    ->label('Metode Pengiriman')
                    ->options([
                        'courier' => 'Kirim Kurir',
                        'pickup' => 'Ambil di Toko',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        if ($data['value'] === 'pickup') {
                            return $query->where('courier', 'pickup');
                        }

                        return $query->where(function ($q) {
                            $q->where('courier', '!=', 'pickup')
                              ->orWhereNull('courier')
                              ->orWhere('courier', '');
                        });
                    }),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Metode Bayar')
                    ->options([
                        'Transfer BCA' => 'Transfer BCA',
                        'Transfer BRI' => 'Transfer BRI',
                        'QRIS' => 'QRIS',
                        'Bank Transfer' => 'Bank Transfer (Lama)',
                    ])
            ])
            ->actions([
                Tables\Actions\Action::make('printReceipt')
                    ->label('Cetak Resi')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (Order $record): string => route('admin.order.print', $record->transaction_id))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
