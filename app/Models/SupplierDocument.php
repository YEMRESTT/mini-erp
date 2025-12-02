<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/*
 * Her tedarikçiye ait PDF, sözleşme, fatura, lisans, garanti belgesi gibi evraklar burada saklanır.

📌 Açıklama
🟦 supplier_id

Bu belge hangi tedarikçiye ait?

🟩 file_path

Örneğin:

storage/suppliers/12/contract_2025.pdf


Laravel burada gerçek dosyayı tutmayacak → sadece yolu kaydedecek.

🟨 description

Belgenin kısa açıklaması:

“2025 yıllık tedarik sözleşmesi”

“Teklif formu”

"Firma vergi levhası PDF"
 */
class SupplierDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'supplier_id',
        'file_path',
        'description',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
