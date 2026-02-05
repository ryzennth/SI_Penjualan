<?php

namespace App\Filament\Resources\Produks\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class ProduksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom nama produk yang bisa dicari.
                TextColumn::make('name')
                    ->searchable(),
                // Kolom foto produk (1 foto ditampilkan, sisanya diringkas).
                ImageColumn::make('photos.photo')
                    ->circular()
                    ->stacked()
                    ->Limit(1)
                    ->LimitedRemainingText(),
                // Kolom thumbnail produk berbentuk kotak.
                ImageColumn::make('thumbnail')
                    ->square(),
                // Kolom harga produk dalam format uang.
                TextColumn::make('price')
                    ->money()
                    ->sortable(),
                // Kolom ukuran produk.
                TextColumn::make('sizes.size')
                    ->searchable()
                    ->sortable(),
                // Kolom stok produk.
                TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                // Kolom brand terkait.
                TextColumn::make('brand.name')
                    ->searchable(),
                // Kolom kategori terkait.
                TextColumn::make('category.name')
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
                // Kolom status produk populer.
                IconColumn::make('is_popular')
                    ->boolean(),
                    ])
            ->filters([
                // Filter untuk menampilkan data yang dihapus (trash).
                TrashedFilter::make(),
            ])
            ->recordActions([
                // Aksi lihat detail produk.
                ViewAction::make(),
                // Aksi edit produk.
                EditAction::make(),
                // Aksi hapus produk
                DeleteAction::make(),
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
