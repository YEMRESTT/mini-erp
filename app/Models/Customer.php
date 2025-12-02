<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerNote;
use App\Models\SalesOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/*
 * 🟦 name, email, phone, address

Müşteri bilgileri.

🟩 is_active

Cron-job tarafından 180 gün sipariş vermeyen müşterileri “pasif” işaretleyeceğiz.
Bu alan tam bunun için.

🟨 İlişkiler:
✔ notes()

Müşteriye yazılan notlar:

"VIP müşteri"

"Ödemeleri geciktiriyor"

"İndirim yapıldı"

Hepsi customer_notes tablosunda tutuluyor.

✔ salesOrders()

Müşterinin verdiği tüm siparişler:

Sipariş geçmişi

Toplam harcama

En çok ne almış?

Aylık sipariş sayısı

 */
class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'is_active',
    ];

    public function notes()
    {
        return $this->hasMany(CustomerNote::class);
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }
}
