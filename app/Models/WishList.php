<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'uuid',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'product_id' => 'integer',
        'uuid'=>'string',
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
