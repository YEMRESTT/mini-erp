<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/*
 * 🟦 total_sales

O haftanın toplam sipariş tutarı.

🟩 top_product

En çok satılan ürünün adı.

🟧 top_customer

Hafta içinde en çok sipariş veren müşteri.
 */

class WeeklyReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'total_sales',
        'top_product',
        'top_customer',
    ];

    // 🔵 En çok satan ürün
    public function product()
    {
        return $this->belongsTo(Product::class, 'top_product');
    }

    // 🔵 En çok sipariş veren müşteri
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'top_customer');
    }
}
