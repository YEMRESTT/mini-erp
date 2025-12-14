<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/*
 * Bu model tedarikçiye verilen siparişlerin “ana kaydıdır”.

Satın alma siparişi olmadan:

Depoya ürün girişi olmaz

Stok artmaz

Geciken sipariş raporu çalışmaz

Tedarikçi performansı ölçülemez

Yani bu model stok artışının babası diyebiliriz

🟦 supplier()

Bu sipariş hangi tedarikçiye verildi?

Örn:

Arçelik Tedarikçi

Trendyol Depo

X Firması

Her satın alma siparişi bir tedarikçiye bağlıdır.

🟩 items()

Satın alma siparişindeki ürünlerin satırları.

Örneğin:

Ürün	Adet	Fiyat
Laptop	5	8.500₺
Mouse	50	80₺
Klavye	30	200₺

Her satır → PurchaseOrderItem modelinde tutulur.

🟥 logs()

Satın alma siparişinin hareket geçmişi:

“Sipariş oluşturuldu”

“Tedarikçi teyidi alındı”

“Teslim tarihi değiştirildi”

“Gecikti”

“Tamamlandı ve stok artırıldı”

Bu loglar hem kullanıcıya görünür hem cron job için veri sağlar.

🟨 status (pending / completed / delayed)

Sipariş akışı şöyle işler:

pending → sipariş verildi

completed → teslim alındı (stok artırılır)

delayed → expected_date geçti ama teslim edilmedi

Cron job: gecikmiş sipariş kontrolü tam buradan çalışır.
 */

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'status',
        'subtotal',
        'vat_total',
        'total',
    ];

    protected $casts = [
        'expected_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }

    public function getCalculatedTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }


    public function logs()
    {
        return $this->hasMany(PurchaseOrderLog::class, 'order_id');
    }

    public function getSubtotalAttribute()
    {
        return $this->total_amount;
    }

    public function getTotalAttribute()
    {
        return $this->total_amount;
    }

}

