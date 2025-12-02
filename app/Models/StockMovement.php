<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;


/*
 * ERP’de her değişikliğin geçmişi olur.
İşte stok giriş/çıkış hareketleri burada loglanır.

🎯 type
in → stok girişi
out → stok çıkışı

🎯 description
İşlem açıklaması:
“Satış siparişi #12 nedeniyle stok düşüldü”
“Depoya yeni ürün girdi”
“Satın alma siparişi #33 teslim edildi”

🎯 user_id
Bu işlemi yapan kullanıcı (admin, çalışan vs.)

🎯 product_id
Hangi ürüne ait stok hareketi?
 *
 * */
class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'type',        // in / out
        'quantity',
        'description',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
