<?php

namespace App\Models;

use App\Http\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable=[
        'uuid',
        'user_id',
        'store_id',
        'status',
        'address',
    ];

    protected $casts=[
        'address'=>'json',
        'status'=>OrderStatus::class,
        'user_id'=>'integer',
        'store_id'=>'integer',
        'uuid'=>'string',
    ];

    public function user():object{
        return $this->belongsTo(User::class);
    }

    public function orderItems(): object
    {
        return $this->hasMany(OrderItem::class);
    }  

    public function store(): object
    {
        return $this->belongsTo(Store::class);
    }
}
