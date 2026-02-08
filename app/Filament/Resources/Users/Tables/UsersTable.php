<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Nama user yang bisa dicari.
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                // Email user yang bisa dicari.
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                // Waktu pembuatan akun.
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Waktu pembaruan data.
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                // Aksi lihat detail user.
                ViewAction::make(),
                // Aksi edit user.
                EditAction::make(),
                // Aksi hapus user.
                DeleteAction::make(),
            ])
            ->toolbarActions([
                // Kumpulan aksi massal untuk data yang dipilih.
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}