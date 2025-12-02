<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SupplierDocument;
use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/*
 * Supplier modeli.
ERP’de ürünleri satın aldığın firmalar burada tutulur.
Satın alma siparişleri (purchase orders) bu modele bağlıdır.

🟦 name, email, phone, address

Tedarikçinin temel kimlik bilgileri.

🟩 İlişkiler:
✔ documents()

Tedarikçiye ait:

PDF sözleşmeler

Teklif dosyaları

Fatura ekleri

Lisans belgeleri

gibi dosyalar burada tutulur.

✔ purchaseOrders()

Bu tedarikçiden verilen tüm satın alma siparişleri:

Ürün tedarik listesi

Teslim tarihleri

Geç gelen siparişler (cron job buradan çalışıyor)

Stok artışı onayları

ERP’nin satın alma akışının temeli buradan oluşuyor.

 */
class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    public function documents()
    {
        return $this->hasMany(SupplierDocument::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
