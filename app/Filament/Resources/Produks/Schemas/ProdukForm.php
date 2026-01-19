<?php

namespace App\Filament\Resources\Produks\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;

class ProdukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->components([
            Grid::make(2)->schema([

                // LEFT
                Grid::make(1)->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->required(),

                    FileUpload::make('thumbnail')
                        ->label('Thumbnail')
                        ->image()
                        ->directory('products/thumbnails')
                        ->required(),

                    Repeater::make('sizes')
                        ->relationship()
                        ->schema([
                            TextInput::make('size')
                                ->label('Size')
                                ->required(),
                        ])
                        ->addActionLabel('Add to sizes')
                        ->columnSpanFull(),
                ]),

                // RIGHT
                Grid::make(1)->schema([
                    TextInput::make('price')
                        ->label('Price')
                        ->prefix('Rp')
                        ->numeric()
                        ->required(),

                    Repeater::make('photos')
                        ->relationship()
                        ->schema([
                            FileUpload::make('photo')
                                ->image()
                                ->directory('Produk-photos')
                                ->required(),
                        ])
                        ->addActionLabel('Add to photos'),
                ]),

            ]),

            // BOTTOM SECTION
            Grid::make(2)->schema([
                Textarea::make('about')
                    ->label('About')
                    ->rows(4),

                Select::make('is_popular')
                    ->label('Is popular')
                    ->options([
                        true => 'Yes',
                        false => 'No',
                    ]),
            ]),

            Grid::make(2)->schema([
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),

                Select::make('brand_id')
                    ->label('Brand')
                    ->relationship('brand', 'name'),
            ]),

            TextInput::make('stock')
                ->label('Stock')
                ->numeric()
                ->suffix('pcs'),
        ]);
}
}
