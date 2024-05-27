<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        'name',
        'store_id',
        'price',
        'category',
        'description',
        'price_type',
        'uuid',
        'condition',
        'delivery_details',
        'images',
        'location',
        'rate',
    ];

    protected $casts = [
        'name'=>'string',
        'store_id'=>'integer',
        'price'=>'float',
        'category' => 'string',
        'description' => 'string',
        'price_type' => 'string',
        'uuid' => 'string',
        'condition' => 'string',
        'delivery_details' => 'string',
        'sales_price'=>'float',
        'images' => 'json',
        'location' => 'string',
        'rate'=>'integer',
    ];

    public function store():object {
        return $this->belongsTo(Store::class);
    }
    
    public function orderItems():object {
        return $this->hasMany(OrderItem::class);
    }

    public function wishLists(): object
    {
        return $this->hasMany(WishList::class);
    }

}
