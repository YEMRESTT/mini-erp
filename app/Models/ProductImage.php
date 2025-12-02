<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/*product_id → Bu resim hangi ürüne ait?

    image_url → Resmin dosya yolu.

is_primary → O ürünün ana fotoğrafı mı?*/


class ProductImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'image_url',
        'is_primary',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
