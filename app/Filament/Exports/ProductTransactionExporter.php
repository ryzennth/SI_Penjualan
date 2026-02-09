<?php

namespace App\Filament\Exports;

use App\Models\ProductTransaction;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ProductTransactionExporter extends Exporter
{
    protected static ?string $model = ProductTransaction::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name'),
            ExportColumn::make('phone'),
            ExportColumn::make('email'),
            ExportColumn::make('booking_trx_id'),
            ExportColumn::make('city'),
            ExportColumn::make('post_code'),
            ExportColumn::make('proof'),
            ExportColumn::make('produk_size'),
            ExportColumn::make('address'),
            ExportColumn::make('quantity'),
            ExportColumn::make('sub_total_amount'),
            ExportColumn::make('grand_total_amount'),
            ExportColumn::make('is_paid'),
            ExportColumn::make('produk.name'),
            ExportColumn::make('promoCode.id'),
            ExportColumn::make('deleted_at'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your product transaction export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
