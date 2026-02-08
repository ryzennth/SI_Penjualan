<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Layout form user dibagi 2 kolom.
                Grid::make(2)->schema([
                    // Nama user.
                    TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(255),
                    // Email user.
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    // Password untuk user (wajib saat create).
                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->required(fn (string $context): bool => $context === 'create')
                        ->minLength(8)
                        ->maxLength(255)
                        ->confirmed()
                        ->dehydrated(fn (?string $state): bool => filled($state)),
                    // Konfirmasi password (tidak disimpan ke database).
                    TextInput::make('password_confirmation')
                        ->label('Confirm password')
                        ->password()
                        ->required(fn (string $context): bool => $context === 'create')
                        ->dehydrated(false),
                ]),
            ]);
    }
}