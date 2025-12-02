<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/*
 * Bir kategori birçok ürüne bağlı olabilir → belongsToMany

Bir kategori üst kategoriye sahip olabilir → parent()

Bir kategorinin alt kategorileri olabilir → children()
 * */
class ProductCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'parent_id',
        'description',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category_pivot');
    }

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }
}
