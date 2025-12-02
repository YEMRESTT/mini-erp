<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/*
 * 🟦 customer_id

Not hangi müşteriye ait?

🟩 user_id

Bu notu hangi kullanıcı/admin ekledi?

🟨 note

"Düzenli müşteri, özel indirim verilebilir"

"Son 2 ödemesi gecikti"

"40 adet ürün istedi (istek kaydı)"

gibi detaylar burada tutulur.
 */
class CustomerNote extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'user_id',
        'note',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
