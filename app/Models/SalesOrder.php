<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\SalesOrderItem;
use App\Models\SalesOrderLog;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/*
 * Bir siparişte:

Hangi müşteri vermiş?

Toplam fiyat ne?

Sipariş hangi aşamada?

Faturalandı mı?

Logları var mı?

hepsi bu model üzerinden yönetilir.

🟦 customer()

Siparişi hangi müşteri vermiş?

🟩 items()

Sipariş içindeki ürünlerin listesi.

🟧 logs()

Siparişin zaman içindeki tüm hareketleri:

Pending → Approved

Approved → Invoiced

Invoiced → Completed

Her değişiklik SalesOrderLog tablosuna yazılır.
Bu özellik ERP'de “sipariş geçmişi” olarak görünür.

🟥 invoice()

Siparişin faturası.

Sipariş onaylanınca fatura oluştururuz.

Fatura HTML/PDF çıktısı buraya bağlıdır.

 */
class SalesOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'status',
        'total',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function logs()
    {
        return $this->hasMany(SalesOrderLog::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
