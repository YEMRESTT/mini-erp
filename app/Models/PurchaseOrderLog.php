<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/*
 * Bu model satın alma siparişinin tüm hareket geçmişini kayıt altına alır.
 *
 *
 * 🟦 order_id

Bu hareket kaydı hangi satın alma siparişine ait?

🟩 user_id

Hangi kullanıcı bu işlemi yaptı?

Stok sorumlusu? Admin? Satın alma yöneticisi?
Hepsini gösterir.

🟧 action

Kayıt altına alınan olay:

Örnekler:

“pending → confirmed”

“expected_date updated: 2025-02-10”

“purchase order delayed”

“purchase order completed, stock increased”

“belge yüklendi: teklif_formu.pdf”
 */


class PurchaseOrderLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'user_id',
        'action',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
