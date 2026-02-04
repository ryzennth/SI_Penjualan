<?php

namespace App\Filament\Resources\Brands;

use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Brands\Pages\EditBrand;
use App\Filament\Resources\Brands\Pages\ListBrands;
use App\Filament\Resources\Brands\Pages\ViewBrand;
use App\Filament\Resources\Brands\Schemas\BrandForm;
use App\Filament\Resources\Brands\Schemas\BrandInfolist;
use App\Filament\Resources\Brands\Tables\BrandsTable;
use App\Models\Brand;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandResource extends Resource
{
    // Model utama yang dikelola oleh resource ini.
    protected static ?string $model = Brand::class;

    // Ikon navigasi di sidebar untuk menu Brand.
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    // Atribut yang ditampilkan sebagai judul record.
    protected static ?string $recordTitleAttribute = 'Brand';

    // Konfigurasi form untuk create/edit brand.
    public static function form(Schema $schema): Schema
    {
        return BrandForm::configure($schema);
    }

    // Konfigurasi infolist untuk tampilan detail brand.
    public static function infolist(Schema $schema): Schema
    {
        return BrandInfolist::configure($schema);
    }

    // Konfigurasi tabel daftar brand.
    public static function table(Table $table): Table
    {
        return BrandsTable::configure($table);
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
            'index' => ListBrands::route('/'),
            'create' => CreateBrand::route('/create'),
            'view' => ViewBrand::route('/{record}'),
            'edit' => EditBrand::route('/{record}/edit'),
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
