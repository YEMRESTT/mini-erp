<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/*
 * 🟦 user_id

Bu bildirim kim içindir?

Admin

Muhasebe

Depo sorumlusu

Satış yöneticisi
vs.

Gönderilen bildirim kişiye özel olabilir.

🟩 type

Bildirim tipi:

critical_stock

new_purchase_order

sales_order_approved

invoice_late

system_cron

Bu alan filtreleme için çok önemli.

🟧 title / message

Panelde göreceğin bildirim başlığı ve açıklaması.

Örnek:

“Kritik Stok Uyarısı”

“Mouse ürünü kritik stok seviyesine düştü.”

🟥 is_read

Kullanıcı okudu mu?
Dashboard’da “0 okunmamış bildirim” gibi göstereceğiz.
 */
class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
