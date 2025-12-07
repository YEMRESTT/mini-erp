<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SalesOrder;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/*
 *
 * Her sipariş satırında:

🟦 order_id

Bu ürün satırı hangi satış siparişine ait?

🟩 product_id

Hangi üründen kaç adet satıldı?

🟧 quantity

Kaç adet satıldı?

🟥 price

O satırdaki birim fiyat (kampanya, indirim olabilir).
 */
class SalesOrderItem extends Model
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

    public function order()
    {
        return $this->belongsTo(\App\Models\SalesOrder::class, 'order_id');
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
