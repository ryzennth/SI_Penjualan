<?php

namespace App\Filament\Resources\ProductTransactions\Schemas;

use App\Models\Produk;
use App\Models\PromoCode;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Wizard\Step;


class ProductTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Wizard untuk membagi form transaksi ke beberapa langkah.
                Wizard::make([
                    Step::make('Product and Price')
                        ->components([
                            // === 1. SELECT PRODUK ===
                            Select::make('produk_id')
                                ->relationship('produk', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $produk = Produk::find($state);
                                    
                                    // Hitung Harga
                                    $price = $produk ? $produk->price : 0;
                                    $quantity = $get('quantity') ?? 1;
                                    $sub_total_amount = $price * $quantity;

                                    $set('price', $price);
                                    $set('sub_total_amount', $sub_total_amount);

                                    // Hitung Grand Total
                                    $discount = $get('discount_amount') ?? 0; // Pastikan nama field konsisten
                                    $grand_total_amount = $sub_total_amount - $discount;
                                    $set('grand_total_amount', $grand_total_amount);

                                    // === PERBAIKAN DISINI ===
                                    // Jangan set 'produk_size' dengan array options. 
                                    // Cukup reset jadi null agar user memilih ulang.
                                    $set('produk_size', null); 
                                }),
                                // Hapus afterStateHydrated yang error tadi.

                            // === 2. SELECT UKURAN (Dynamic Options) ===
                            Select::make('produk_size')
                                ->label('Ukuran')
                                ->options(function (callable $get) {
                                    // Ambil ID produk yang sedang dipilih
                                    $produkId = $get('produk_id');

                                    if (!$produkId) {
                                        return [];
                                    }

                                    // Ambil daftar ukuran berdasarkan produk tersebut
                                    // Pastikan relasi 'sizes' ada di model Produk
                                    return Produk::find($produkId)
                                        ?->sizes
                                        ->pluck('size', 'id')
                                        ->toArray() ?? [];
                                })
                                ->required()
                                ->live(), // Tetap live jika berpengaruh ke stock (opsional)

                            // === 3. QUANTITY ===
                            Select::make('quantity')
                                ->required()
                                ->prefix('Qty')
                                ->options([
                                    1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 
                                    // ... tambahkan sesuai kebutuhan atau ganti jadi TextInput numeric
                                ])
                                ->default(1)
                                ->live()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    // Ambil harga yang sudah diset sebelumnya
                                    $produkId = $get('produk_id');
                                    $price = $produkId ? (Produk::find($produkId)->price ?? 0) : 0;
                                    
                                    $quantity = intval($state);
                                    $sub_total_amount = $price * $quantity;
                                    
                                    $set('sub_total_amount', $sub_total_amount);
                                    
                                    $discount = $get('discount_amount') ?? 0;
                                    $grand_total_amount = $sub_total_amount - $discount;
                                    $set('grand_total_amount', $grand_total_amount);
                                }),

                            // === 4. PROMO CODE ===
                            Select::make('promo_code_id')
                                ->relationship('promoCode', 'code') // Biasanya fieldnya 'code' bukan 'id' untuk label
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $sub_total_amount = $get('sub_total_amount') ?? 0;
                                    $promoCode = PromoCode::find($state);
                                    
                                    $discount = $promoCode ? $promoCode->discount_amount : 0;
                                    
                                    $set('discount_amount', $discount);
                                    
                                    $grand_total_amount = $sub_total_amount - $discount;
                                    $set('grand_total_amount', max($grand_total_amount, 0)); // Cegah minus
                                }),

                            // === 5. HASIL PERHITUNGAN ===
                            TextInput::make('sub_total_amount')
                                ->label('Sub Total')
                                ->required()
                                ->numeric()
                                ->readOnly()
                                ->prefix('IDR'),

                            TextInput::make('discount_amount')
                                ->label('Discount Amount')
                                ->required()
                                ->numeric()
                                ->readOnly() // Sebaiknya readOnly karena hasil dari promo code
                                ->default(0)
                                ->prefix('IDR'),
                                
                            TextInput::make('grand_total_amount')
                                ->label('Grand Total')
                                ->required()
                                ->numeric()
                                ->readOnly()
                                ->prefix('IDR'),
                        ]),
                

                Step::make( 'Customer Information')
                    ->components([
                        // Informasi pelanggan ditata dalam grid 2 kolom.
                        Grid::make( 2)
                        ->components([
                            // Nama pelanggan.
                            TextInput::make('name')
                                ->required()
                                ->maxLength( 255),
                            // Email pelanggan.
                            TextInput::make('email')
                                ->required()
                                ->email()
                                ->maxLength( 255),
                            // Nomor telepon pelanggan.
                            TextInput::make('phone')
                                ->required()
                                ->maxLength(20),
                            // Alamat pelanggan.
                            TextInput::make('address')
                                ->required()
                                ->maxLength(500),
                            // Kota pelanggan.
                            TextInput::make('city')
                                ->required()
                                ->maxLength(100),
                            // Kode pos pelanggan.
                            TextInput::make('post_code')
                                ->required()
                                ->maxLength(20),
                        ]),
                    ]),
                Step::make('Payment Information')
                    ->components([
                        // Kode booking transaksi.
                        TextInput::make('booking_trx_id')
                            ->required()
                            ->maxLength(200),
                        // Status pembayaran menggunakan tombol pilihan.
                        ToggleButtons::make('is_paid')
                            ->label('Apakah Sudah Membayar')
                            ->boolean()
                            ->grouped()
                        
                            ->icons([
                                true => 'heroicon-o-pencil',
                                false => 'heroicon-o-clock',
                            ])
                            ->required(),
                        // Bukti pembayaran berupa gambar.
                        FileUpload::make('proof')
                            ->required()
                            ->image(),
                    ]),
            ])
                    ->columnSpan('full')
                    ->columns(1)
                    ->skippable(),
                
            ]);
    }
}
