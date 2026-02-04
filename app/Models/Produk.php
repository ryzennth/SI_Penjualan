<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory, SoftDeletes;


    // Kolom yang boleh diisi massal.
    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'about',
        'price',
        'stock',
        'is_popular',
        'category_id',
        'brand_id',
    ];

    // Otomatis membuat slug saat nama di-set.
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Relasi ke kategori produk.
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, foreignKey: 'category_id');
    }

    // Relasi ke brand produk.
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, foreignKey: 'brand_id');
    }

    // Relasi ke foto-foto produk.
    public function photos(): HasMany
    {
        return $this->hasMany(ProdukPhoto::class);
    }

    // Relasi ke ukuran-ukuran produk.
    public function sizes(): HasMany
    {
        return $this->hasMany(ProdukSize::class);
    }
}
