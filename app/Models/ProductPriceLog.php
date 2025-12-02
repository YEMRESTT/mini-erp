<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;


/*
 *Bu model sayesinde bir ürünün geçmişte hangi
 fiyata indiğini, çıktıgını, ne zaman değiştiğini takip edeceğiz
 * old_price → Eski fiyat

new_price → Yeni fiyat

changed_by → Bu fiyatı değiştiren kullanıcı (user_id)

product_id → Hangi ürüne ait fiyat güncellemesi?*/


class ProductPriceLog extends Model
{
    protected $fillable = [
        'product_id',
        'old_price',
        'new_price',
        'changed_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
