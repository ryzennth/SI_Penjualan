<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Input nama brand: wajib diisi agar setiap brand punya identitas.
                TextInput::make('name')
                    ->required()
                    ,
                // Upload logo brand: hanya gambar, disimpan di folder brands, ukuran maks 1MB.
                // Field ini wajib, namun menerima nilai null saat tidak ada unggahan.
                FileUpload::make('logo')
                    ->image()
                    ->directory( 'brands')
                    ->maxSize(1024)
                    ->required()
                    ->nullable(),
            ]);
    }
}
