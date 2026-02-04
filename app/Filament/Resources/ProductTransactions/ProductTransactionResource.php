<?php

namespace App\Filament\Resources\ProductTransactions;

use App\Filament\Resources\ProductTransactions\Pages\CreateProductTransaction;
use App\Filament\Resources\ProductTransactions\Pages\EditProductTransaction;
use App\Filament\Resources\ProductTransactions\Pages\ListProductTransactions;
use App\Filament\Resources\ProductTransactions\Pages\ViewProductTransaction;
use App\Filament\Resources\ProductTransactions\Schemas\ProductTransactionForm;
use App\Filament\Resources\ProductTransactions\Schemas\ProductTransactionInfolist;
use App\Filament\Resources\ProductTransactions\Tables\ProductTransactionsTable;
use App\Models\ProductTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductTransactionResource extends Resource
{
    // Model utama yang dikelola oleh resource ini.
    protected static ?string $model = ProductTransaction::class;

    // Ikon navigasi di sidebar untuk menu Product Transaction.
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    // Atribut yang ditampilkan sebagai judul record.
    protected static ?string $recordTitleAttribute = 'ProductTransaction';

    // Konfigurasi form untuk create/edit transaksi.
    public static function form(Schema $schema): Schema
    {
        return ProductTransactionForm::configure($schema);
    }

    // Konfigurasi infolist untuk tampilan detail transaksi.
    public static function infolist(Schema $schema): Schema
    {
        return ProductTransactionInfolist::configure($schema);
    }

    // Konfigurasi tabel daftar transaksi.
    public static function table(Table $table): Table
    {
        return ProductTransactionsTable::configure($table);
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
            'index' => ListProductTransactions::route('/'),
            'create' => CreateProductTransaction::route('/create'),
            'view' => ViewProductTransaction::route('/{record}'),
            'edit' => EditProductTransaction::route('/{record}/edit'),
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
