<?php

namespace App\Filament\Resources\PromoCodes;

use App\Filament\Resources\PromoCodes\Pages\CreatePromoCode;
use App\Filament\Resources\PromoCodes\Pages\EditPromoCode;
use App\Filament\Resources\PromoCodes\Pages\ListPromoCodes;
use App\Filament\Resources\PromoCodes\Pages\ViewPromoCode;
use App\Filament\Resources\PromoCodes\Schemas\PromoCodeForm;
use App\Filament\Resources\PromoCodes\Schemas\PromoCodeInfolist;
use App\Filament\Resources\PromoCodes\Tables\PromoCodesTable;
use App\Models\PromoCode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromoCodeResource extends Resource
{
    // Model utama yang dikelola oleh resource ini.
    protected static ?string $model = PromoCode::class;

    // Ikon navigasi di sidebar untuk menu Promo Code.
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    // Atribut yang ditampilkan sebagai judul record.
    protected static ?string $recordTitleAttribute = 'PromoCode';

    // Konfigurasi form untuk create/edit promo code.
    public static function form(Schema $schema): Schema
    {
        return PromoCodeForm::configure($schema);
    }

    // Konfigurasi infolist untuk tampilan detail promo code.
    public static function infolist(Schema $schema): Schema
    {
        return PromoCodeInfolist::configure($schema);
    }

    // Konfigurasi tabel daftar promo code.
    public static function table(Table $table): Table
    {
        return PromoCodesTable::configure($table);
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
            'index' => ListPromoCodes::route('/'),
            'create' => CreatePromoCode::route('/create'),
            'view' => ViewPromoCode::route('/{record}'),
            'edit' => EditPromoCode::route('/{record}/edit'),
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
