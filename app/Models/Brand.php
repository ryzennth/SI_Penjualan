<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    // Kolom yang boleh diisi massal.
    protected $fillable = [
        'name',
        'slug',
        'logo',
    ];

    // Otomatis membuat slug saat nama di-set.
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Relasi ke produk yang berada di bawah brand ini.
    public function produks(): HasMany
    {
        return $this->hasMany(Produk::class);
    }
}
