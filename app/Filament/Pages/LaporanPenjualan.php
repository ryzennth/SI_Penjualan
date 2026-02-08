<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use App\Models\ProductTransaction;
use Filament\Tables\Filters\Filter;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Concerns\InteractsWithTable;

class LaporanPenjualan extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.laporan-penjualan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static ?string $navigationLabel = 'Sell Report';

    protected static ?string $title = 'Sell Report';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ProductTransaction::query()
                    ->with(['produk', 'promoCode'])
            )
            ->columns([
                TextColumn::make('booking_trx_id')
                    ->label('Kode Transaksi')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('produk.name')
                    ->label('Produk')
                    ->searchable(),
                TextColumn::make('promoCode.code')
                    ->label('Promo Code')
                    ->placeholder('-'),
                TextColumn::make('quantity')
                    ->label('Qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sub_total_amount')
                    ->label('Sub Total')
                    ->money('IDR')
                    ->sortable()
                    ->summarize(Sum::make()->label('Total Sub Total')),
                TextColumn::make('grand_total_amount')
                    ->label('Grand Total')
                    ->money('IDR')
                    ->sortable()
                    ->summarize(Sum::make()->label('Total Grand Total')),
                IconColumn::make('is_paid')
                    ->label('Lunas')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->label('Periode')
                    ->form([
                        DatePicker::make('date_from')
                            ->label('Dari'),
                        DatePicker::make('date_until')
                            ->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date)
                            );
                    }),
                SelectFilter::make('produk_id')
                    ->label('Produk')
                    ->relationship('produk', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('is_paid')
                    ->label('Status Pembayaran')
                    ->options([
                        1 => 'Lunas',
                        0 => 'Belum Lunas',
                    ]),
            ])
            ->headerActions([
                // Pastikan menggunakan ExportAction dari pxlrbt (sesuai import di atas)
                ExportAction::make()
                    ->label('Export Excel')
                    ->exports([
                        ExcelExport::make()
                            ->withFilename('Laporan_Penjualan_' . date('Y-m-d'))
                            ->withColumns([
                                Column::make('booking_trx_id')->heading('Kode Transaksi'),
                                Column::make('name')->heading('Pelanggan'),
                                Column::make('produk.name')->heading('Produk'),
                                Column::make('promoCode.code')->heading('Promo Code'),
                                Column::make('quantity')->heading('Qty'),
                                Column::make('sub_total_amount')->heading('Sub Total'),
                                Column::make('grand_total_amount')->heading('Grand Total'),
                                Column::make('is_paid')
                                    ->heading('Lunas')
                                    ->formatStateUsing(fn ($state) => $state ? 'Ya' : 'Tidak'), // Format manual boolean
                                Column::make('created_at')->heading('Tanggal'),
                            ]),
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
