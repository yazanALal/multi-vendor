<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rate',
        'comment'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'product_id' => 'integer',
        'rate'=>'integer',
        'comment'=>'string',
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
