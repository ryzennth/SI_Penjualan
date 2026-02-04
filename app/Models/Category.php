<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    // Kolom yang boleh diisi massal.
    protected $fillable = [
        'name',
        'slug',
        'icon',
    ];

    // Relasi ke produk yang berada di bawah kategori ini.
    public function produks(): HasMany
    {
        return $this->hasMany(Produk::class);
    }

    // Otomatis membuat slug saat nama di-set.
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);

    }
}
