<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required(),
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($set, $state) => $set('slug', \Illuminate\Support\Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),
                                Forms\Components\TextInput::make('stock')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'AVAILABLE' => 'AVAILABLE',
                                        'SOLD OUT' => 'SOLD OUT',
                                    ])
                                    ->default('AVAILABLE')
                                    ->required(),
                                Forms\Components\TextInput::make('roast_level')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('altitude')
                                    ->maxLength(100),
                            ]),
                        Forms\Components\TextInput::make('sensory_notes')
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('image_path')
                            ->image()
                            ->directory('images/products')
                            ->imageResizeMode('force')
                            ->imageCropAspectRatio('1:1')
                            ->label('Product Image')
                            ->maxSize(1024)
                            ->validationMessages([
                                'max' => 'Ukuran file foto terlalu besar (maksimal 1MB).',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_best_seller')
                            ->default(false),
                    ])->columnSpan(2),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Repeater::make('images')
                            ->relationship('images')
                            ->schema([
                                Forms\Components\FileUpload::make('image_path')
                                    ->image()
                                    ->directory('images/products/gallery')
                                    ->imageResizeMode('force')
                                    ->imageCropAspectRatio('1:1')
                                    ->label('Foto Sisi Lain')
                                    ->maxSize(1024)
                                    ->validationMessages([
                                        'max' => 'Ukuran file foto terlalu besar (maksimal 1MB).',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->label('Urutan'),
                            ])
                            ->grid(3)
                            ->label('Galeri Foto Tambahan (Tampilan Sisi Lain)')
                            ->default([])
                    ])->columnSpan(2),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Repeater::make('sizes')
                            ->schema([
                                Forms\Components\TextInput::make('size')
                                    ->required(),
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),
                            ])
                            ->default([
                                ['size' => '100gr', 'price' => 0]
                            ])
                            ->columns(2)
                            ->label('Packaging Varian Size & Price')
                    ])->columnSpan(1)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->numeric(decimalPlaces: 0, decimalSeparator: ',', thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'AVAILABLE' => 'success',
                        'SOLD OUT' => 'danger',
                        default => 'neutral'
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_best_seller')
                    ->boolean()
                    ->label('Best Seller')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'AVAILABLE' => 'AVAILABLE',
                        'SOLD OUT' => 'SOLD OUT',
                    ])
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
