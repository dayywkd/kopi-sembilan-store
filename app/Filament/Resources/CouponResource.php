<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Kupon / Promo';

    protected static ?string $pluralModelLabel = 'Kupon / Promo';

    protected static ?string $modelLabel = 'Kupon';

    protected static ?string $navigationGroup = 'Manajemen Toko';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kupon')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Kupon')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g. KOPI9HEMAT')
                            ->dehydrateStateUsing(fn ($state) => strtoupper(trim($state))),
                        Forms\Components\Select::make('type')
                            ->label('Tipe Potongan')
                            ->options([
                                'fixed' => 'Potongan Tetap (Rupiah)',
                                'percent' => 'Persentase (%)',
                            ])
                            ->required()
                            ->default('fixed')
                            ->native(false),
                        Forms\Components\TextInput::make('value')
                            ->label('Nilai Potongan')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->placeholder('e.g. 10000 atau 10'),
                        Forms\Components\TextInput::make('minimum_subtotal')
                            ->label('Minimum Belanja (Rp)')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->minValue(0)
                            ->placeholder('e.g. 50000'),
                    ])->columns(2),

                Forms\Components\Section::make('Batasan & Validitas')
                    ->schema([
                        Forms\Components\TextInput::make('usage_limit')
                            ->label('Batas Penggunaan (Kali)')
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('Kosongkan jika tidak dibatasi'),
                        Forms\Components\TextInput::make('used_count')
                            ->label('Sudah Digunakan (Kali)')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Tanggal Kedaluwarsa')
                            ->placeholder('Kosongkan jika aktif selamanya')
                            ->native(false),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Kupon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'fixed' => 'success',
                        'percent' => 'info',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'fixed' => 'Potongan Tetap',
                        'percent' => 'Persentase',
                    }),
                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai')
                    ->sortable()
                    ->formatStateUsing(function (Coupon $record, string $state) {
                        return $record->type === 'percent' ? $state . '%' : 'Rp ' . number_format((float) $state, 0, ',', '.');
                    }),
                Tables\Columns\TextColumn::make('minimum_subtotal')
                    ->label('Min. Belanja')
                    ->sortable()
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('usage_limit')
                    ->label('Limit')
                    ->formatStateUsing(fn ($state) => $state ?? '∞'),
                Tables\Columns\TextColumn::make('used_count')
                    ->label('Dipakai')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Kedaluwarsa')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
