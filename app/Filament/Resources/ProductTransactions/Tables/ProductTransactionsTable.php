<?php

namespace App\Filament\Resources\ProductTransactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom nama pelanggan yang bisa dicari.
                TextColumn::make('name')
                    ->searchable(),
                // Kolom nomor telepon pelanggan.
                TextColumn::make('phone')
                    ->searchable(),
                // Kolom email pelanggan.
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                // Kolom kode booking transaksi.
                TextColumn::make('booking_trx_id')
                    ->searchable(),
                // Kolom kota pelanggan.
                TextColumn::make('city')
                    ->searchable(),
                // Kolom kode pos pelanggan.
                TextColumn::make('post_code')
                    ->searchable(),
                // Kolom bukti pembayaran (path/filename).
                TextColumn::make('proof')
                    ->searchable(),
                // Kolom ukuran produk.
                TextColumn::make('produk_size')
                    ->numeric()
                    ->sortable(),
                // Kolom jumlah produk.
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                // Kolom subtotal transaksi.
                TextColumn::make('sub_total_amount')
                    ->numeric()
                    ->sortable(),
                // Kolom total akhir setelah diskon.
                TextColumn::make('grand_total_amount')
                    ->numeric()
                    ->sortable(),
                // Kolom status pembayaran (ikon boolean).
                IconColumn::make('is_paid')
                    ->boolean(),
                // Kolom nama produk terkait.
                TextColumn::make('produk.name')
                    ->searchable(),
                // Kolom promo code terkait.
                TextColumn::make('promoCode.id')
                    ->searchable(),
                // Kolom waktu penghapusan (soft delete), disembunyikan default.
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Kolom waktu dibuat, disembunyikan default.
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Kolom waktu diubah, disembunyikan default.
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter untuk menampilkan data yang dihapus (trash).
                TrashedFilter::make(),
            ])
            ->recordActions([
                // Aksi lihat detail transaksi.
                ViewAction::make(),
                // Aksi edit transaksi.
                EditAction::make(),
            ])
            ->toolbarActions([
                // Kumpulan aksi massal untuk data terpilih.
                BulkActionGroup::make([
                    // Hapus massal (soft delete).
                    DeleteBulkAction::make(),
                    // Hapus permanen massal.
                    ForceDeleteBulkAction::make(),
                    // Pulihkan data yang dihapus.
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
