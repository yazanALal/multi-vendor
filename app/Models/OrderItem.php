<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable=[
        'uuid',
        'order_id',
        'product_id',
        'price',
        'quantity',
    ];

    protected $casts=[
        'uuid'=>'string',
        'order_id'=>'integer',
        'product_id' => 'integer',
        'price'=>'float',
        'quantity' => 'integer',
    ];


    public function order():object{
        return $this->belongsTo(Order::class);
    }

    public function product():object{
        return $this->belongsTo(Product::class);
    }
}
