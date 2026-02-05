<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom nama kategori yang bisa dicari.
                TextColumn::make('name')
                    ->searchable(),
                // Kolom slug kategori yang bisa dicari untuk identifikasi URL/slug.
                TextColumn::make('slug')
                    ->searchable(),
                // Kolom ikon kategori berbentuk lingkaran untuk tampilan rapi.
                ImageColumn::make('icon')
                    ->circular(),
                // Kolom waktu dibuat, disembunyikan secara default dan bisa diurutkan.
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Kolom waktu diubah, disembunyikan secara default dan bisa diurutkan.
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
                // Aksi lihat detail kategori.
                ViewAction::make(),
                // Aksi edit data kategori.
                EditAction::make(),
                // Aksi hapus data kategori
                DeleteAction::make(),
            ])
            ->toolbarActions([
                // Kumpulan aksi massal untuk data yang dipilih.
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
