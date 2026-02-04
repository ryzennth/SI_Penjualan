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
                        // Pilih produk, lalu hitung otomatis harga, subtotal, diskon, dan ukuran yang tersedia.
                        Select::make('produk_id')
                            ->relationship('produk', 'name')
                            ->required()
                            ->searchable()
                            ->live()
                            ->preload()
                            ->afterStateUpdated( function ($state, callable $get, callable $set) {
                                $produk = Produk::find( $state);
                                $price = $produk ? $produk->price : 0;
                                $quantity = $get('quantity') ?? 1;
                                $sub_total_amount = $price * $quantity;

                                $set('price', $price);
                                $set('sub_total_amount', $sub_total_amount);
                            
                                $discount = $get('promo_code_id') ?? 0;
                                $grand_total_amount = $sub_total_amount - $discount;
                                $set('grand_total_amount', $grand_total_amount);

                                $sizes = $produk ? $produk->sizes->pluck('size', 'id')->toArray() : [];
                                $set('produk_size', $sizes);

                            })

                            // Saat edit data, preload ukuran produk sesuai produk yang sudah dipilih.
                            ->afterStateHydrated( function ($state, callable $get, callable $set) {
                                $produkId = $state;
                                if ($produkId) {
                                    $produk = Produk::find($produkId);
                                    $sizes = $produk ? $produk->sizes->pluck('size', 'id')->toArray() : [];
                                    $set('produk_size', $sizes);                              
                            
                            }
                        }),

                        // Pilih ukuran produk dari daftar yang diisi otomatis.
                        Select::make('produk_size')
                            ->options( function (callable $get) {
                                $sizes = $get('produk_size');
                                return is_array($sizes) ? $sizes : [];
                            })
                            ->required()
                            ->live(),

                        // Jumlah produk, memicu perhitungan subtotal dan grand total.
                        Select::make('quantity')
                            ->required()
                            
                            ->prefix( 'Qty')
                            ->live()
                            ->afterStateUpdated( function ($state, callable $get, callable $set) {
                                $price = $get('price');
                                $quantity = $state;
                                $sub_total_amount = $price * $quantity;
                                $set('sub_total_amount', $sub_total_amount);
                                $discount = $get('discount') ?? 0;
                                $grand_total_amount = $sub_total_amount - $discount;
                                $set('grand_total_amount', $grand_total_amount);

                            }),

                        // Pilih promo code untuk mengisi diskon otomatis.
                        Select::make('promo_code_id')
                            ->relationship('promoCode', 'id')
                            ->required()
                            ->preload()
                            ->live()
                            ->afterStateUpdated( function ($state, callable $get, callable $set) {
                                $sub_total_amount = $get('sub_total_amount');
                                $promoCode = PromoCode::find( $state);
                                $discount = $promoCode ? $promoCode->discount_amount : 0;
                                $set('discount_amount', $discount);
                                $grand_total_amount = $sub_total_amount - $discount;
                                $set('grand_total_amount', $grand_total_amount);
                            }),

                            // Subtotal otomatis dari harga x jumlah.
                            TextInput::make('sub_total_amount')
                                ->required()
                                ->numeric()
                                ->readOnly()
                                ->prefix( 'IDR'),
                            // Nominal diskon yang diterapkan.
                            TextInput::make( 'discount_amount')
                                ->label('Discount Amount')
                                ->required()
                                ->numeric()
                                ->prefix( 'IDR'),

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
