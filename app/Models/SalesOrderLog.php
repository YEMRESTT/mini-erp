<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SalesOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/*
 * Bu model, bir satış siparişinin zaman içindeki tüm durum değişikliklerini tutar.
 *
 *
 * 🟦 order_id

Hangi siparişin hareketi?

🟩 user_id

Bu sipariş durumunu kim değiştirdi?

Çok değerli çünkü:

Hangi çalışan/satış temsilcisi ne yapmış?

Sipariş hangi adımlardan geçmiş?

Ne zaman onaylandı?

Ne zaman faturalandı?

Ne zaman tamamlandı?

Siparişi kim iptal etmiş?

Hepsi burada tutulur.

🟧 action

Örnek log kayıtları:

"pending → approved"

"approved → invoiced"

"invoiced → completed"

"ürün miktarı güncellendi"

"sipariş iptal edildi"

Bu sayede sipariş geçmişi müşteriye bile gösterilebilir.
 */

class SalesOrderLog extends Model
{
use HasFactory;
    protected $fillable = [
        'order_id',
        'user_id',
        'action',
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
