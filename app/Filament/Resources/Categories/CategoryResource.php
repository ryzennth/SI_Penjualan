<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Pages\ViewCategory;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Categories\Schemas\CategoryInfolist;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    // Model utama yang dikelola oleh resource ini.
    protected static ?string $model = Category::class;

    // Ikon navigasi di sidebar untuk menu Category.
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    // Atribut yang ditampilkan sebagai judul record.
    protected static ?string $recordTitleAttribute = 'Category';

    // Konfigurasi form untuk create/edit kategori.
    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    // Konfigurasi infolist untuk tampilan detail kategori.
    public static function infolist(Schema $schema): Schema
    {
        return CategoryInfolist::configure($schema);
    }

    // Konfigurasi tabel daftar kategori.
    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'view' => ViewCategory::route('/{record}'),
            'edit' => EditCategory::route('/{record}/edit'),
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
