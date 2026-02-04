<?php

namespace App\Filament\Resources\PromoCodes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PromoCodesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom kode promo yang bisa dicari.
                TextColumn::make('code')
                    ->searchable(),
                // Kolom nominal diskon.
                TextColumn::make('discount_amount')
                    ->numeric()
                    ->sortable(),
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
                // Aksi lihat detail promo code.
                ViewAction::make(),
                // Aksi edit promo code.
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
