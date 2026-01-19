<?php

namespace App\Models;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukSize extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'size',
        'produk_id',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, foreignKey: 'produk_id');
    }
}
