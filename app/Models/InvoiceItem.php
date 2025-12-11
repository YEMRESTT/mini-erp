<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/*
 * Bir fatura tek bir şey değildir; içinde birden
 * fazla ürün satırı veya ücret kalemi olabilir.
 *Örneğin bir fatura:

Laptop x 2 → 40.000₺

Mouse x 3 → 900₺

KDV → 7.820₺

İşte bu kalemlerin her biri InvoiceItem tablosunda tutulur.
 *
 *
 * 🟦 invoice_id

Bu satır hangi faturaya ait?

🟩 product_id

Faturadaki ürün ID'si.
Örneğin:

Laptop

Monitör

Ofis sandalyesi

KDV satırı gibi özel satırlar için product_id NULL olabilir.
O yüzden migration’da nullable yapmıştık.

🟧 description

Satır açıklaması:

“Dell XPS 15 2025 Edition”

“KDV %20”

“Yazılım hizmeti”

🟥 quantity

Kaç adet ürün?

🟪 amount

Bu satırın toplam tutarı.

Genelde:
→ ürün fiyatı × quantity
→ veya hizmet kalemi ücreti
 */
class InvoiceItem extends Model
{
    use HasFactory;
    protected $fillable = ['invoice_id', 'product_id', 'quantity', 'price'];

    protected $casts = [
        'amount' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
