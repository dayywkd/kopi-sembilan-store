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
                        Forms\Components\Select::make('status')
                            ->options([
                                'Awaiting Payment' => 'Awaiting Payment',
                                'Paid' => 'Paid',
                                'Packing' => 'Packing',
                                'Shipped' => 'Shipped',
                                'Delivered' => 'Delivered',
                                'Cancelled' => 'Cancelled',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('tracking_number')
                            ->maxLength(255)
                            ->label('Nomor Resi / Tracking Number'),
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
                    })
            ])
            ->actions([
                Tables\Actions\Action::make('printReceipt')
                    ->label('Cetak Resi')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (Order $record): string => route('admin.order.print', $record->transaction_id))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
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
