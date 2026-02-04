<?php

namespace App\Filament\Resources\Produks\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;

class ProdukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->components([
            // Layout utama form dibagi menjadi dua kolom.
            Grid::make(2)->schema([

                // LEFT
                Grid::make(1)->schema([
                    // Nama produk, wajib diisi.
                    TextInput::make('name')
                        ->label('Name')
                        ->required(),

                    // Thumbnail produk, hanya gambar.
                    FileUpload::make('thumbnail')
                        ->label('Thumbnail')
                        ->image()
                        ->directory('products/thumbnails')
                        ->required(),

                    // Daftar ukuran produk yang dapat ditambah lebih dari satu.
                    Repeater::make('sizes')
                        ->relationship()
                        ->schema([
                            TextInput::make('size')
                                ->label('Size')
                                ->required(),
                        ])
                        ->addActionLabel('Add to sizes')
                        ->columnSpanFull(),
                ]),

                // RIGHT
                Grid::make(1)->schema([
                    // Harga produk dengan prefix Rupiah.
                    TextInput::make('price')
                        ->label('Price')
                        ->prefix('Rp')
                        ->numeric()
                        ->required(),

                    // Galeri foto produk, bisa lebih dari satu.
                    Repeater::make('photos')
                        ->relationship()
                        ->schema([
                            FileUpload::make('photo')
                                ->image()
                                ->directory('Produk-photos')
                                ->required(),
                        ])
                        ->addActionLabel('Add to photos'),
                ]),

            ]),

            // BOTTOM SECTION
            Grid::make(2)->schema([
                // Deskripsi singkat tentang produk.
                Textarea::make('about')
                    ->label('About')
                    ->rows(4),

                // Penanda apakah produk populer.
                Select::make('is_popular')
                    ->label('Is popular')
                    ->options([
                        true => 'Yes',
                        false => 'No',
                    ]),
            ]),

            Grid::make(2)->schema([
                // Relasi kategori produk.
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),

                // Relasi brand produk.
                Select::make('brand_id')
                    ->label('Brand')
                    ->relationship('brand', 'name'),
            ]),

            // Stok produk dalam satuan pcs.
            TextInput::make('stock')
                ->label('Stock')
                ->numeric()
                ->suffix('pcs'),
        ]);
}
}
