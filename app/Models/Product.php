<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// BURASI ÖNEMLİ: İlişkili modeller eklenmeli
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductPriceLog;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\SalesOrderItem;
use App\Models\PurchaseOrderItem;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'status',
        'description',
    ];

    public static function booted()
    {
        static::updated(function ($product) {
            if ($product->isDirty('price')) {
                $product->priceLogs()->create([
                    'old_price' => $product->getOriginal('price'),
                    'new_price' => $product->price,
                ]);
            }
        });
    }


    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category_pivot');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function priceLogs()
    {
        return $this->hasMany(ProductPriceLog::class);
    }

    public function stock()
    {
        return $this->hasOne(ProductStock::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function salesItems()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
