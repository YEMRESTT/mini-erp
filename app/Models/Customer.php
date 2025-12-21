<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','email','phone','address','city','birth_date',
        'vat_number','profile_photo','segment','loyalty_points',
        'status','last_order_date'
    ];



    public function notes()
    {
        return $this->hasMany(CustomerNote::class);
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'customer_id');
    }


    public function lastOrder()
    {
        return $this->salesOrders()->latest()->first();
    }

    public function isActive()
    {
        return $this->lastOrder() &&
            $this->lastOrder()->created_at > now()->subDays(180);
    }

}
