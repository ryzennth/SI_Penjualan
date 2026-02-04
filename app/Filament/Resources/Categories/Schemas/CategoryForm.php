<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Input nama kategori: wajib diisi dengan batas 255 karakter.
                TextInput::make('name')
                    ->required()
                    ->maxLength(length: 255),
                // Upload ikon kategori: hanya gambar, disimpan di folder categories, ukuran maks 1MB.
                // Field ini wajib, namun menerima nilai null saat tidak ada unggahan.
                FileUpload::make('icon')
                    ->image()
                    ->directory( 'categories')
                    ->maxSize(1024)
                    ->required()
                    ->nullable(),
            ]);
    }
}
