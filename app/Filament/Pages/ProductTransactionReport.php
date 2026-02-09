<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Actions\ExportAction;
use App\Filament\Exports\ProductTransactionExporter;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use App\Models\ProductTransaction;

use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ProductTransactionReport extends Page implements HasTable
{
  use InteractsWithTable;

  protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentChartBar;
  protected static string|UnitEnum|null $navigationGroup = 'Reports';
  protected string $view = 'filament.pages.product-transaction-report';

  public function table(Table $table): Table
  {
    return $table
      ->query(ProductTransaction::query())
      ->columns([
        TextColumn::make('booking_trx_id')
          ->label('Booking Trx ID')
          ->searchable(),

        ImageColumn::make('produk.thumbnail')
          ->label('Product')
          ->circular(),
        
        TextColumn::make('name')
          ->label('Name')
          ->searchable(),

        IconColumn::make('is_paid')
          ->label('Paid')
          ->boolean(),
      ]);
  }

  protected function getHeaderActions(): array
  {
    return [
      ExportAction::make()
        ->label('Export Product Transactions')
        ->exporter(ProductTransactionExporter::class),
    ];
  }
}
