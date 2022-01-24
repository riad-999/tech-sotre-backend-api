<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    public function address()
    {
        return $this->hasOne(OrderAddress::class, 'order_id');
    }
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'order_product',
            'order_id',
            'product_id'
        )->withPivot('quantity');
    }
}