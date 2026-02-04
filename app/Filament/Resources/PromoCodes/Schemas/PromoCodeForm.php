<?php

namespace App\Filament\Resources\PromoCodes\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;

class PromoCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Layout form promo code dibagi 2 kolom.
                Grid::make(2)->schema([
                
                // Kode promo yang akan digunakan pelanggan.
                TextInput::make('code')
                    ->label('Code')
                    ->required()
                    ->maxLength(50),

                // Nominal diskon dalam Rupiah.
                TextInput::make('discount_amount')
                    ->label('Discount amount')
                    ->prefix('IDR')
                    ->numeric()
                    ->required(),
                ]),
            ]);
    }
}
