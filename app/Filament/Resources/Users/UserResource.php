<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Schemas\UserInfolist;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource
{
    // Model utama yang dikelola oleh resource ini.
    protected static ?string $model = User::class;

    // Ikon navigasi di sidebar untuk menu User.
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    // Atribut yang ditampilkan sebagai judul record.
    protected static ?string $recordTitleAttribute = 'name';

    // Konfigurasi form untuk create/edit user.
    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    // Konfigurasi infolist untuk tampilan detail user.
    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    // Konfigurasi tabel daftar user.
    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}