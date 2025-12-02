<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/*
 * Bir ürünün mevcut stok miktarı burada tutuluyor.
Stok hareketleri başka tabloda (stock_movements),
 ama “son güncel stok” buradan okunur.
 *
 * quantity → Ürünün şu anki stok miktarı

product_id → Hangi ürüne ait

Bu model sayesinde:

✔️ Dashboard → “Stok Sayısı”
✔️ Ürün detay → “Mevcut stok”
✔️ Kritik stok → bildirim tetikleme
✔️ Satış → stok düşme
✔️ Satın alma → stok artırma

gibi yüzlerce yerde stok durumu okunur.*/

class ProductStock extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
