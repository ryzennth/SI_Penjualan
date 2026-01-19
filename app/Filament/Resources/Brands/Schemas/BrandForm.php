<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ,
                FileUpload::make('logo')
                    ->image()
                    ->directory( 'brands')
                    ->maxSize(1024)
                    ->required()
                    ->nullable(),
            ]);
    }
}
