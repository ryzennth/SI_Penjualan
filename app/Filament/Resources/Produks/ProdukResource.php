<?php

namespace App\Filament\Resources\Produks;

use App\Filament\Resources\Produks\Pages\CreateProduk;
use App\Filament\Resources\Produks\Pages\EditProduk;
use App\Filament\Resources\Produks\Pages\ListProduks;
use App\Filament\Resources\Produks\Pages\ViewProduk;
use App\Filament\Resources\Produks\Schemas\ProdukForm;
use App\Filament\Resources\Produks\Schemas\ProdukInfolist;
use App\Filament\Resources\Produks\Tables\ProduksTable;
use App\Models\Produk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProdukResource extends Resource
{
    // Model utama yang dikelola oleh resource ini.
    protected static ?string $model = Produk::class;

    // Ikon navigasi di sidebar untuk menu Produk.
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    // Atribut yang ditampilkan sebagai judul record.
    protected static ?string $recordTitleAttribute = 'Produk';

    // Konfigurasi form untuk create/edit produk.
    public static function form(Schema $schema): Schema
    {
        return ProdukForm::configure($schema);
    }

    // Konfigurasi infolist untuk tampilan detail produk.
    public static function infolist(Schema $schema): Schema
    {
        return ProdukInfolist::configure($schema);
    }

    // Konfigurasi tabel daftar produk.
    public static function table(Table $table): Table
    {
        return ProduksTable::configure($table);
    }

    // Relasi resource (kosong jika tidak ada).
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // Mapping halaman resource.
    public static function getPages(): array
    {
        return [
            'index' => ListProduks::route('/'),
            'create' => CreateProduk::route('/create'),
            'view' => ViewProduk::route('/{record}'),
            'edit' => EditProduk::route('/{record}/edit'),
        ];
    }

    // Mengambil query record tanpa scope soft delete bawaan.
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
