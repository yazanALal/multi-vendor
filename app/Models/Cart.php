<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'product_id',
        'user_id',
        'price',
        'quantity',
    ];

    protected $casts = [
        'uuid' => 'string',
        'user_id' => 'integer',
        'product_id' => 'integer',
        'price' => 'float',
        'quantity' => 'integer',
    ];

    public function product(): object
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): object
    {
        return $this->belongsTo(User::class);
    }
}
