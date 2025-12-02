<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseOrder;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/*
 * 🟦 order_id

Bu satır hangi satın alma siparişine bağlı?

Örn:

PO #12 → Mouse 200 adet

PO #12 → Klavye 150 adet

🟩 product_id

Hangi ürün sipariş edilmiş?

🟧 quantity

Kaç adet sipariş edildi?

Depo teslim aldığında bu adet kadar stok artırılır (otomasyon ile).

🟥 price

Ürünün satın alma fiyatı.
 */


class PurchaseOrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
