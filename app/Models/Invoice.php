<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SalesOrder;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/*
 * bu model satış siparişlerinden üretilen faturaların ana kaydıdır.
 *
 *
 * 🟦 sales_order_id

Bu fatura hangi satış siparişine ait?

Örnek:

Sipariş #88 → Fatura #120

ERP’de sipariş → fatura ilişkisi 1'e 1’dir, yani bir siparişten bir fatura çıkar.

🟩 due_date

Faturanın son ödeme tarihi.

Cron job şunu yapacak:

Eğer due_date geçtiyse → status = late

Admin'e bildirim gidecek

Tam ticari ERP davranışı. 🔥

🟧 status

pending → fatura kesildi ama ödenmedi

paid → ödeme alındı

late → son ödeme tarihi geçti

🟥 items()

Faturanın satırları (ürünler, ücretler, KDV satırları...)
 */

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'sales_order_id',
        'due_date',
        'status',   // pending, paid, late
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function order()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

}
